<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $spending_count = Order::where('status', 'pending')
            ->where('user_id', Auth::id())
            ->count();

        return view('admin.orders.index', compact('orders', 'spending_count'));
    }
}
