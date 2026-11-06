<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === '1');
        }

        // Filter berdasarkan rentang harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter berdasarkan stok
        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->where('stock', '<=', 5);
            } elseif ($request->stock === 'out') {
                $query->where('stock', 0);
            }
        }

        // Urutkan berdasarkan parameter
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'stock_asc':
                    $query->orderBy('stock', 'asc');
                    break;
                case 'stock_desc':
                    $query->orderBy('stock', 'desc');
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(10)->appends($request->query());
        $categories = Category::all();

        // Tambahkan data untuk chart
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
            // chart remains empty on error
        }

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
            'chartData' => $chartData
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'description' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);

        $imagePath = null;
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $imageUrl = Storage::url($imagePath);
        }

        Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'image_url' => $imageUrl,
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0|regex:/^\d*\.?\d{0,2}$/',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'price.regex' => 'Format harga tidak valid. Gunakan format angka dengan maksimal 2 desimal.'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        // Upload dan simpan gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('public/products', $filename);
            $validated['image'] = str_replace('public/', '', $path);
            $validated['image_url'] = Storage::url($path);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            Log::info('Mencoba menghapus produk ID: ' . $id);

            // Hapus order_items terkait terlebih dahulu
            DB::table('order_items')->where('product_id', $id)->delete();

            // Hapus cart_items terkait
            DB::table('cart_items')->where('product_id', $id)->delete();

            // Hapus gambar jika ada
            if ($product->image) {
                $imagePath = 'public/' . $product->image;
                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                    Log::info('Gambar berhasil dihapus: ' . $imagePath);
                }
            }

            // Hapus produk
            $product->delete();

            DB::commit();
            Log::info('Produk berhasil dihapus');

            return redirect('/admin/products')
                ->with('success', 'Produk berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus produk: ' . $e->getMessage());

            return redirect('/admin/products')
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function updateStatus(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active
        ]);

        return back()->with('success', 'Status produk berhasil diperbarui');
    }
}
