@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.admin')

@section('title', 'Manajemen Produk - ' . config('app.name'))

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Tambah Produk
        </a>
    </div>

    <!-- Filter & Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Cari produk...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" width="80">#</th>
                            <th scope="col">Produk</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Stok</th>
                            <th scope="col">Status</th>
                            <th scope="col" width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="rounded"
                                             width="48"
                                             height="48"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width: 48px; height: 48px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="ms-3">
                                        <div class="fw-semibold">{{ $product->name }}</div>
                                        <div class="small text-muted">{{ Str::limit($product->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $product->category->name }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($product->price) }}</td>
                            <td>
                                @if($product->stock <= 5)
                                    <span class="badge bg-danger">Stok Menipis</span>
                                @endif
                                {{ $product->stock }}
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           {{ $product->is_active ? 'checked' : '' }}
                                           onchange="updateStatus({{ $product->id }}, this)">
                                </div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                    <!-- Tombol hapus produk -->
                                    <button class="btn btn-danger" onclick="confirmDelete('{{ $product->id }}')">Hapus</button>

                                    <!-- Form untuk menghapus produk -->
                                    <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox display-6 d-block mb-3"></i>
                                    <p class="mb-0">Belum ada produk</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Menampilkan {{ $products->firstItem() ?? 0 }} sampai {{ $products->lastItem() ?? 0 }}
                    dari {{ $products->total() }} produk
                </div>
                {{ $products->withQueryString()->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(productId) {
    Swal.fire({
        title: 'Hapus Produk?',
        text: "Apakah Anda yakin ingin menghapus produk ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengonfirmasi, kirim permintaan penghapusan
            document.getElementById('delete-form-' + productId).submit();
        }
    });
}
</script>
@endpush
