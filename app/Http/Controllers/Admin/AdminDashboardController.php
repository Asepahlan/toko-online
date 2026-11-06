<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Statistik dasar
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

        // Pesanan terbaru
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Data untuk grafik penjualan
        $chartData = $this->getSalesChartData();

        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'recentOrders',
            'chartData'
        ));
    }

    private function getSalesChartData()
    {
        $data = array_fill(0, 12, 0);

        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                $salesData = Order::where('status', 'completed')
                    ->whereYear('created_at', date('Y'))
                    ->select(
                        DB::raw('MONTH(created_at) as month'),
                        DB::raw('SUM(total_amount) as total')
                    )
                    ->groupBy('month')
                    ->get();
            } else {
                // SQLite compatible
                $salesData = Order::where('status', 'completed')
                    ->whereYear('created_at', date('Y'))
                    ->get()
                    ->groupBy(function($order) {
                        return (int) $order->created_at->format('n');
                    })
                    ->map(function($group) {
                        return $group->sum('total_amount');
                    });

                foreach ($salesData as $month => $total) {
                    $data[$month - 1] = (float) $total;
                }

                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                return ['labels' => $months, 'data' => $data];
            }

            foreach ($salesData as $sale) {
                $data[$sale->month - 1] = (float) $sale->total;
            }
        } catch (\Throwable $e) {
            // Return empty data if DB error (e.g., during testing)
        }

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        return ['labels' => $months, 'data' => $data];
    }

    public function getStats()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'recent_orders' => Order::with(['user', 'orderItems'])
                                   ->select('orders.*')
                                   ->join('users', 'users.id', '=', 'orders.user_id')
                                   ->latest()
                                   ->take(5)
                                   ->get()
                                   ->map(function($order) {
                                       return [
                                           'id' => $order->id,
                                           'customer' => $order->user ? $order->user->name : 'Guest',
                                           'total' => $order->total_amount,
                                           'status' => $this->getStatusLabel($order->status ?? 'pending'),
                                           'date' => $order->created_at->format('d M Y H:i')
                                       ];
                                   }),
            'sales_data' => $this->getSalesData()
        ];

        return response()->json($stats);
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'pending' => '<span class="badge text-bg-warning">Menunggu Pembayaran</span>',
            'processing' => '<span class="badge text-bg-info">Sedang Diproses</span>',
            'shipped' => '<span class="badge text-bg-primary">Dikirim</span>',
            'completed' => '<span class="badge text-bg-success">Selesai</span>',
            'cancelled' => '<span class="badge text-bg-danger">Dibatalkan</span>'
        ];

        return $labels[$status] ?? '<span class="badge text-bg-secondary">Unknown</span>';
    }

    private function getSalesData()
    {
        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                return Order::where('status', 'completed')
                    ->whereBetween('created_at', [now()->startOfYear(), now()])
                    ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
                    ->groupBy('month')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->month => (float) $item->total];
                    })
                    ->toArray();
            } else {
                return Order::where('status', 'completed')
                    ->whereBetween('created_at', [now()->startOfYear(), now()])
                    ->get()
                    ->groupBy(function($order) {
                        return (int) $order->created_at->format('n');
                    })
                    ->map(fn($group) => (float) $group->sum('total_amount'))
                    ->toArray();
            }
        } catch (\Throwable $e) {
            return [];
        }
    }
}
