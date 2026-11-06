@extends('layouts.admin')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Detail Produk</h6>
                <div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <button type="button"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="img-fluid rounded">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                             style="height: 300px;">
                            <i class="fas fa-image fa-3x text-secondary"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table">
                        <tr>
                            <th width="200">Nama Produk</th>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $product->category->name }}</td>
                        </tr>
                        <tr>
                            <th>Harga</th>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Stok</th>
                            <td>
                                @if($product->stock <= 5)
                                    <span class="badge bg-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($product->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>{{ $product->description }}</td>
                        </tr>
                        <tr>
                            <th>Dibuat pada</th>
                            <td>{{ $product->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir diupdate</th>
                            <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong>{{ $product->name }}</strong>?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Tindakan ini tidak dapat dibatalkan
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
