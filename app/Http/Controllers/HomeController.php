<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Cache;


class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(4)
            ->get();

        $categories = Category::withCount('products')->get();

        return view('home', compact('categories', 'featuredProducts'));
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
}
