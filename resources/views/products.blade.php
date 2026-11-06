@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center">
        <h1 class="text-2xl font-bold">Daftar Produk</h1>
        <div class="w-full md:w-auto mt-4 md:mt-0">
            <form action="{{ route('products') }}" method="GET" class="flex gap-2">
                <input type="text"
                       name="search"
                       placeholder="Cari produk..."
                       value="{{ request('search') }}"
                       class="flex-1 md:w-64 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Cari
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Filter -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-20">
                <h2 class="font-semibold text-lg mb-4">Filter</h2>

                <!-- Kategori -->
                <div class="mb-6">
                    <h3 class="font-medium text-gray-900 mb-2">Kategori</h3>
                    <div class="space-y-2">
                        <a href="{{ route('products') }}"
                           class="block text-gray-600 hover:text-blue-600 {{ !request('category') ? 'text-blue-600 font-medium' : '' }}">
                            Semua Kategori
                        </a>
                        @foreach($categories ?? $allCategories ?? [] as $category)
                            <a href="{{ route('products', ['category' => $category->slug]) }}"
                               class="block text-gray-600 hover:text-blue-600 {{ request('category') == $category->slug ? 'text-blue-600 font-medium' : '' }}">
                                {{ $category->name }}
                                <span class="text-gray-500 text-sm">({{ $category->products_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Urutkan -->
                <div>
                    <h3 class="font-medium text-gray-900 mb-2">Urutkan</h3>
                    <div class="space-y-2">
                        <a href="{{ route('products', array_merge(request()->query(), ['sort' => 'latest'])) }}"
                           class="block text-gray-600 hover:text-blue-600 {{ request('sort') == 'latest' ? 'text-blue-600 font-medium' : '' }}">
                            Terbaru
                        </a>
                        <a href="{{ route('products', array_merge(request()->query(), ['sort' => 'price_low'])) }}"
                           class="block text-gray-600 hover:text-blue-600 {{ request('sort') == 'price_low' ? 'text-blue-600 font-medium' : '' }}">
                            Harga: Rendah ke Tinggi
                        </a>
                        <a href="{{ route('products', array_merge(request()->query(), ['sort' => 'price_high'])) }}"
                           class="block text-gray-600 hover:text-blue-600 {{ request('sort') == 'price_high' ? 'text-blue-600 font-medium' : '' }}">
                            Harga: Tinggi ke Rendah
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="lg:col-span-3">
            @if($products->isEmpty())
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <p class="text-gray-500">Tidak ada produk yang ditemukan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <img src="{{ $product->image_url }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-48 object-cover hover:opacity-90 transition-opacity">
                            </a>
                            <div class="p-4">
                                <a href="{{ route('products.show', $product->slug) }}"
                                   class="block font-semibold text-lg mb-2 hover:text-blue-600">
                                    {{ $product->name }}
                                </a>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-blue-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                    @if($product->stock > 0)
                                        <span class="text-green-600 text-sm">Stok: {{ $product->stock }}</span>
                                    @else
                                        <span class="text-red-600 text-sm">Stok Habis</span>
                                    @endif
                                </div>
                                @auth
                                    @if($product->stock > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                                Tambah ke Keranjang
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="block text-center mt-4 text-blue-600 hover:text-blue-800">
                                        Login untuk membeli
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
