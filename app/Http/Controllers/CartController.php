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

    public function add(Product $product)
    {
        try {
            // Debug untuk melihat data produk
            Log::info('Product Data:', $product->only(['id', 'name', 'image', 'image_url']));

            Cart::add([
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'attributes' => $product->only(['image', 'image_url', 'stock'])
            ]);

            return response()->json([
                'success' => true,
                'cartCount' => Cart::getTotalQuantity(),
                'message' => 'Produk berhasil ditambahkan ke keranjang'
            ]);

        } catch (\Exception $e) {
            Log::error('Error adding to cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan ke keranjang'
            ], 500);
        }
    }

    public function remove($id)
    {
        if (!Cart::get($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang'
            ], 404);
        }

        try {
            Cart::remove($id);
            return response()->json([
                'success' => true,
                'cartCount' => Cart::getTotalQuantity(),
                'total' => Cart::getTotal(),
                'message' => 'Item berhasil dihapus dari keranjang'
            ]);
        } catch (\Exception $e) {
            Log::error('Error removing from cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item dari keranjang'
            ], 500);
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
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan'
                ], 404);
            }

            // Cek stok
            $product = Product::find($id);
            if ($request->quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 400);
            }

            Cart::update($id, [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity
                ]
            ]);

            $updatedItem = Cart::get($id);

            return response()->json([
                'success' => true,
                'cartCount' => Cart::getTotalQuantity(),
                'subtotal' => $updatedItem->getPriceSum(),
                'total' => Cart::getTotal(),
                'message' => 'Jumlah item berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate jumlah item'
            ], 500);
        }
    }

    public function clear()
    {
        try {
            Cart::clear();
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan'
            ]);
        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan keranjang'
            ], 500);
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
