<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik dasar
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalRevenue = Order::sum('total');

        // Data untuk grafik 7 hari terakhir
        $chartLabels = collect();
        $chartData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels->push($date->format('d M'));

            $dailyRevenue = Order::whereDate('created_at', $date)
                ->sum('total');
            $chartData->push($dailyRevenue);
        }

        return view('admin.dashboard.index', compact(
            'totalProducts',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'chartLabels',
            'chartData'
        ));
    }
}
