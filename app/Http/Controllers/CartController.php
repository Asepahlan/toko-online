<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk menggunakan Auth

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getContent();
        $total = Cart::getTotal();
        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        try {
            // Debug untuk melihat data produk
            Log::info('Product Data:', $product->only(['id', 'name', 'image', 'image_url']));

            $qty = (int) $request->input('quantity', 1);
            if ($qty < 1) $qty = 1;

            Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'attributes' => $product->only(['image', 'image_url', 'stock'])
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'cartCount' => Cart::getTotalQuantity(),
                    'message' => 'Produk berhasil ditambahkan ke keranjang'
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');

        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menambahkan ke keranjang'
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat menambahkan ke keranjang.');
        }
    }

    public function remove(Request $request, $id)
    {
        if (!Cart::get($id)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan di keranjang'
                ], 404);
            }
            return back()->with('error', 'Item tidak ditemukan di keranjang.');
        }

        try {
            Cart::remove($id);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'cartCount' => Cart::getTotalQuantity(),
                    'total' => Cart::getTotal(),
                    'message' => 'Item berhasil dihapus dari keranjang'
                ]);
            }

            return back()->with('success', 'Item berhasil dihapus dari keranjang.');

        } catch (\Exception $e) {
            Log::error('Error removing from cart: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus item dari keranjang'
                ], 500);
            }

            return back()->with('error', 'Gagal menghapus item dari keranjang.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|numeric|min:1'
            ]);

            $item = Cart::get($id);
            if (!$item) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Item tidak ditemukan'
                    ], 404);
                }
                return back()->with('error', 'Item tidak ditemukan.');
            }

            // Cek stok
            $product = Product::find($id);
            if ($product && $request->quantity > $product->stock) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi'
                    ], 400);
                }
                return back()->with('error', 'Stok produk tidak mencukupi.');
            }

            Cart::update($id, [
                'quantity' => [
                    'relative' => false,
                    'value' => (int) $request->quantity
                ]
            ]);

            $updatedItem = Cart::get($id);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'cartCount' => Cart::getTotalQuantity(),
                    'subtotal' => $updatedItem->getPriceSum(),
                    'total' => Cart::getTotal(),
                    'message' => 'Jumlah item berhasil diupdate'
                ]);
            }

            return back()->with('success', 'Jumlah barang berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error updating cart: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupdate jumlah item'
                ], 500);
            }

            return back()->with('error', 'Gagal memperbarui jumlah barang.');
        }
    }

    public function clear(Request $request)
    {
        try {
            Cart::clear();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil dikosongkan'
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Keranjang belanja berhasil dikosongkan.');

        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengosongkan keranjang'
                ], 500);
            }

            return back()->with('error', 'Gagal mengosongkan keranjang belanja.');
        }
    }

    public function count()
    {
        return response()->json([
            'count' => Cart::getTotalQuantity()
        ]);
    }

    public function getTotal()
    {
        return response()->json([
            'total' => Cart::getTotal()
        ]);
    }
}
