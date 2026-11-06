@extends('layouts.admin')

@section('title', 'Kategori - Admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
            <h1 class="h3 mb-2">Kategori</h1>
            <p class="text-muted">Kelola kategori produk</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($categories->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-folder2-open text-muted display-4"></i>
                    <p class="text-muted mt-3">Belum ada kategori</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th>Jumlah Produk</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->products_count }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn btn-light"
                                           title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <!-- Tombol hapus kategori -->
                                        <button class="btn btn-danger" onclick="confirmDelete('{{ $category->id }}')">Hapus</button>

                                        <!-- Form untuk menghapus kategori -->
                                        <form id="delete-form-{{ $category->id }}" action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            @if($categories->isNotEmpty())
                                Menampilkan {{ $categories->count() }} kategori
                            @else
                                Tidak ada data kategori
                            @endif
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table > :not(caption) > * > * {
    padding: 1rem;
}
.btn-group .btn {
    padding: .375rem .5rem;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(categoryId) {
    Swal.fire({
        title: 'Hapus Kategori?',
        text: "Yakin ingin menghapus kategori ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengonfirmasi, kirim permintaan penghapusan
            document.getElementById('delete-form-' + categoryId).submit();
        }
    });
}
</script>
@endpush
