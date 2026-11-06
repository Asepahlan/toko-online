<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        $totalPenjualan = Order::where('status', 'completed')->sum('total_amount');
        $totalOrders = Order::count();
        $completedOrdersCount = Order::where('status', 'completed')->count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        $produkTerlaris = Product::withCount('orderItems')
                                ->orderBy('order_items_count', 'desc')
                                ->take(10)
                                ->get();

        $recentOrders = Order::with('user')
                            ->latest()
                            ->take(10)
                            ->get();

        return view('admin.reports.index', compact(
            'totalPenjualan',
            'totalOrders',
            'completedOrdersCount',
            'pendingOrdersCount',
            'produkTerlaris',
            'recentOrders'
        ));
    }

    public function exportSalesReport(Request $request)
    {
        $fileName = 'laporan-penjualan-' . date('Y-m-d') . '.csv';

        $orders = Order::with('user')
            ->where('status', 'completed')
            ->latest()
            ->get();

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ['ID Pesanan', 'No. Invoice', 'Tanggal', 'Nama Pelanggan', 'Email', 'No. Telepon', 'Alamat Pengiriman', 'Total (Rp)', 'Status']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->invoice_number ?? ('#' . $order->id),
                    $order->created_at->format('Y-m-d H:i'),
                    $order->user ? $order->user->name : '-',
                    $order->user ? $order->user->email : '-',
                    $order->contact_number ?? ($order->user ? $order->user->phone : '-'),
                    $order->shipping_address ?? '-',
                    $order->total_amount,
                    $order->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
