<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Illuminate\Support\Facades\Log; // Tambahkan ini
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Darryldecode\Cart\Facades\CartFacade as Cart; // Tambahkan ini

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            if (Cart::isEmpty()) {
                return redirect()->back()->with('error', 'Keranjang belanja kosong');
            }

            // Generate order ID
            $lastOrder = Order::latest()->first();
            $newId = $lastOrder ? $lastOrder->id + 1 : 1;

            // Buat order baru
            $order = Order::create([
                'user_id' => Auth::id(),
                'id' => $newId,
                'total_amount' => Cart::getTotal(),
                'status' => Order::STATUS_PENDING,
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
            ]);

            // Simpan item order
            foreach (Cart::getContent() as $item) {
                $product = Product::findOrFail($item->id);

                // Validasi stok
                if ($product->stock < $item->quantity) {
                    DB::rollback();
                    return redirect()->back()->with('error', "Stok {$product->name} tidak mencukupi");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);

                // Update stok produk
                $product->decrement('stock', $item->quantity);
            }

            // Kosongkan keranjang
            Cart::clear();

            DB::commit();
            return redirect()->route('orders.show', $order->id)
                           ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order creation error: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat membuat pesanan');
        }
    }

    public function show(Order $order)
    {
        // Pastikan user hanya bisa lihat pesanannya sendiri
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->latest()
                      ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function cancel(Order $order)
    {
        try {
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            if ($order->status !== Order::STATUS_PENDING) {
                return back()->with('error', 'Hanya pesanan dengan status menunggu yang dapat dibatalkan');
            }

            DB::beginTransaction();

            // Kembalikan stok
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $order->update(['status' => Order::STATUS_CANCELLED]);

            DB::commit();

            return back()->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order cancellation error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan');
        }
    }

    public function removeProductFromOrder(Order $order, $productId)
    {
        try {
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            // Cek apakah order memiliki item tersebut
            $orderItem = $order->items()->where('product_id', $productId)->first();
            if (!$orderItem) {
                return back()->with('error', 'Item tidak ditemukan dalam pesanan');
            }

            // Kembalikan stok produk
            $orderItem->product->increment('stock', $orderItem->quantity);

            // Hapus item dari order
            $orderItem->delete();

            return back()->with('success', 'Item berhasil dihapus dari pesanan');

        } catch (\Exception $e) {
            Log::error('Error removing product from order: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus item dari pesanan');
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = null;
        if ($orderId) {
            $order = Order::with('orderItems.product')->where('user_id', Auth::id())->find($orderId);
        }

        if (!$order) {
            return redirect()->route('orders.index');
        }

        return view('checkout.success', compact('order'));
    }

    public function resendConfirmation(Order $order)
    {
        try {
            // Pastikan hanya pemilik order yang bisa kirim ulang
            if ($order->user_id !== Auth::id()) {
                return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini');
            }

            // Pastikan status masih pending
            if ($order->status !== Order::STATUS_PENDING) {
                return back()->with('error', 'Hanya pesanan dengan status menunggu yang dapat dikirim ulang');
            }

            // Format pesan WhatsApp
            $message = urlencode(
                "Halo Admin AhlanBoys, saya ingin konfirmasi pesanan:\n\n" .
                "No. Pesanan: #" . $order->id . "\n" .
                "Nama Penerima: " . Auth::user()->name . "\n" . // Menambahkan nama akun
                "Tanggal Pesanan: " . $order->created_at->format('d F Y H:i') . "\n" .
                "Total: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n" .
                "Mohon diproses, terima kasih."
            );

            // Nomor WhatsApp admin (ganti dengan nomor yang benar)
            $waNumber = "6285215142110";

            // Generate link WhatsApp
            $waLink = "https://wa.me/{$waNumber}?text=" . $message;

            return redirect()->away($waLink);

        } catch (\Exception $e) {
            Log::error('Resend confirmation error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengirim ulang konfirmasi');
        }
    }
}
