<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class AdminDashboardComposer
{
    public function compose(View $view)
    {
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            'data' => array_fill(0, 12, 0)
        ];

        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                $salesData = DB::table('orders')
                    ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
                    ->whereYear('created_at', date('Y'))
                    ->groupBy('month')
                    ->get();

                foreach ($salesData as $sale) {
                    $chartData['data'][$sale->month - 1] = (float) $sale->total;
                }
            } else {
                $sales = Order::whereYear('created_at', date('Y'))->get();
                foreach ($sales as $sale) {
                    $m = (int) $sale->created_at->format('n');
                    $chartData['data'][$m - 1] += (float) ($sale->total_amount ?? 0);
                }
            }
        } catch (\Throwable $e) {
            // Keep empty chart on error
        }

        $view->with('chartData', $chartData);
    }
}
