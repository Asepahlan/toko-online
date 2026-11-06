<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getContent();
        $total = Cart::getTotal();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda kosong!');
        }

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (Cart::isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong!');
        }

        try {
            DB::beginTransaction();

            $totalAmount = Cart::getTotal();

            // Validate stock for each cart item
            foreach (Cart::getContent() as $item) {
                $product = Product::findOrFail($item->id);
                if ($product->stock < $item->quantity) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', "Stok untuk produk {$product->name} tidak mencukupi (Tersisa: {$product->stock})");
                }
            }

            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'total_amount' => $totalAmount,
                'status' => Order::STATUS_PENDING,
                'shipping_address' => $validated['address'],
                'contact_number' => $validated['phone'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create Order Items and update product stock
            foreach (Cart::getContent() as $item) {
                $product = Product::findOrFail($item->id);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);

                $product->decrement('stock', $item->quantity);

                if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'total_sales')) {
                    $product->increment('total_sales', $item->quantity);
                }
                if (\Illuminate\Support\Facades\Schema::hasColumn('products', 'sold_count')) {
                    $product->increment('sold_count', $item->quantity);
                }
            }

            // Clear cart
            Cart::clear();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan konfirmasi pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }
}
