<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        // Filter berdasarkan kategori menggunakan slug
        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter dan pencarian lainnya
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        // Ambil produk terkait berdasarkan kategori
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('products', 'public');
            $data['image'] = $path;
        }

        Product::create($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $path = $image->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        // Hapus gambar produk
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }

    public function category(Category $category)
    {
        $products = Product::where('category_id', $category->id)
                          ->paginate(12);

        return view('products.index', [
            'products' => $products,
            'category' => $category
        ]);
    }

    public function new()
    {
        $products = Product::latest()->paginate(12);
        return view('products.index', compact('products'));
    }

    public function popular()
    {
        $products = Product::withCount('orders')
            ->orderByDesc('orders_count')
            ->paginate(12);
        return view('products.index', compact('products'));
    }
}
