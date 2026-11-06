@extends('layouts.admin')

@section('title', isset($category) ? 'Edit Kategori - ' . config('app.name') : 'Tambah Kategori - ' . config('app.name'))

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">{{ isset($category) ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h1>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
                          method="POST"
                          class="needs-validation"
                          novalidate>
                        @csrf
                        @if(isset($category))
                            @method('PUT')
                        @endif

                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="bi bi-tag me-1"></i>Nama Kategori
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $category->name ?? '') }}"
                                   placeholder="Masukkan nama kategori"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Nama kategori akan otomatis dikonversi menjadi slug.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="bi bi-card-text me-1"></i>Deskripsi
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Masukkan deskripsi kategori"
                                      required>{{ old('description', $category->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>{{ isset($category) ? 'Update Kategori' : 'Simpan Kategori' }}
                            </button>
                        </div>
                    </form>

                    @if(isset($category))
                    <!-- Tombol Hapus di Tabel Kategori -->
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm rounded-circle delete-btn">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-control-lg {
    font-size: 1rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* Smooth transitions */
.modal,
.modal-backdrop,
.fade,
.collapse,
.collapsing {
    transition: all 0.2s ease-in-out !important;
}

/* Prevent flickering on hover/active states */
.btn,
.nav-link,
.dropdown-item,
.form-control,
.form-select {
    transition: all 0.2s ease-in-out !important;
    backface-visibility: hidden;
    transform: translateZ(0);
    -webkit-font-smoothing: subpixel-antialiased;
}

/* Prevent layout shifts */
.modal-open {
    padding-right: 0 !important;
}

.modal {
    padding-right: 0 !important;
}

/* Smooth animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.animate-fade {
    animation: fadeIn 0.2s ease-in-out;
}

.animate-slide {
    animation: slideIn 0.2s ease-in-out;
}

/* Hardware acceleration */
* {
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
}

/* Prevent text flickering */
body {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* Stabilize layout */
.container,
.container-fluid {
    transform: translate3d(0, 0, 0);
    backface-visibility: hidden;
    perspective: 1000px;
}
</style>
@endpush

@push('scripts')
<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()

// Konfirmasi Hapus
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus kategori ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Notifikasi sukses
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        toast: true,
        position: 'top-end'
    });
@endif

// Notifikasi error
@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
        toast: true,
        position: 'top-end'
    });
@endif
</script>
@endpush
