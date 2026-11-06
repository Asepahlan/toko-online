<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        // Hitung jumlah pesanan per status
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        return view('admin.orders.index', compact(
            'orders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders'
        ));
    }

    public function show(Order $order)
    {
        try {
            // Load relasi yang diperlukan
            $order->load(['user', 'orderItems.product']);

            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Error loading order details: ' . $e->getMessage());
            return back()->withErrors('Gagal memuat detail pesanan');
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            // Validasi status yang dikirim
            $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled'
            ]);

            $order->update([
                'status' => $request->status
            ]);

            return back()->with('success', 'Status pesanan berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return back()->withErrors('Gagal mengupdate status pesanan');
        }
    }

    public function destroy(Order $order)
    {
        try {
            // Hapus order items terlebih dahulu jika ada relasi
            $order->orderItems()->delete();

            // Hapus order
            $order->delete();

            return back()->with('success', 'Pesanan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting order: ' . $e->getMessage());
            return back()->withErrors('Gagal menghapus pesanan');
        }
    }

    public function filter(Request $request)
    {
        try {
            $query = Order::query();

            // Filter berdasarkan status
            if ($request->status) {
                $query->where('status', $request->status);
            }

            // Filter berdasarkan tanggal
            if ($request->date_from) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->date_to) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Filter berdasarkan pencarian
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->whereHas('user', function($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%")
                          ->orWhere('email', 'like', "%{$request->search}%");
                    })
                    ->orWhere('id', 'like', "%{$request->search}%");
                });
            }

            $orders = $query->with(['user', 'orderItems.product'])
                          ->latest()
                          ->paginate(10);

            return view('admin.orders.index', [
                'orders' => $orders,
                'pendingOrders' => Order::where('status', 'pending')->count(),
                'processingOrders' => Order::where('status', 'processing')->count(),
                'completedOrders' => Order::where('status', 'completed')->count(),
                'cancelledOrders' => Order::where('status', 'cancelled')->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error filering orders: ' . $e->getMessage());
            return back()->withErrors('Gagal memfilter pesanan');
        }
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function cancel(Order $order)
    {
        $order->update([
            'status' => 'cancelled'
        ]);

        return back()->with('success', 'Pesanan berhasil dibatalkan');
    }
}
