@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Filter</h5>
        </div>
        <div class="card-body">
            <form id="filterForm" class="row g-3" action="{{ route('admin.users.index') }}" method="GET">
                <!-- Filter Role -->
                <div class="col-12 col-md-4">
                    <label class="form-label">Role</label>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="role" id="all-role" value="" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="all-role">
                            Semua
                        </label>

                        <input type="radio" class="btn-check" name="role" id="admin-role" value="admin">
                        <label class="btn btn-outline-primary btn-sm" for="admin-role">
                            Admin
                        </label>

                        <input type="radio" class="btn-check" name="role" id="user-role" value="user">
                        <label class="btn btn-outline-secondary btn-sm" for="user-role">
                            User
                        </label>
                    </div>
                </div>

                <!-- Filter Status -->
                <div class="col-12 col-md-4">
                    <label class="form-label">Status</label>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="status" id="all-status" value="" checked>
                        <label class="btn btn-outline-secondary btn-sm" for="all-status">
                            Semua
                        </label>

                        <input type="radio" class="btn-check" name="status" id="active-status" value="active">
                        <label class="btn btn-outline-success btn-sm" for="active-status">
                            Aktif
                        </label>

                        <input type="radio" class="btn-check" name="status" id="inactive-status" value="inactive">
                        <label class="btn btn-outline-danger btn-sm" for="inactive-status">
                            Tidak Aktif
                        </label>
                    </div>
                </div>

                <!-- Pencarian -->
                <div class="col-12 col-md-4">
                    <label class="form-label">Cari</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput"
                               placeholder="Cari nama atau email..." name="search" value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            Cari
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Pengguna -->
    <div class="card shadow-sm mt-3">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">Daftar Pengguna</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-3">NAMA</th>
                            <th class="border-0">EMAIL</th>
                            <th class="border-0">ROLE</th>
                            <th class="border-0">STATUS</th>
                            <th class="border-0">BERGABUNG</th>
                            <th class="border-0 text-end px-3">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="px-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                                         style="width: 35px; height: 35px; background-color: {{ '#' . substr(md5($user->name), 0, 6) }};">
                                        <span class="text-white fw-bold">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>{{ $user->name }}</div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} status-badge"
                                      data-user-id="{{ $user->id }}">
                                    {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-end px-3">
                                <div class="btn-group">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm rounded-circle">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm rounded-circle delete-btn">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editUserForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Edit Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-select" name="role" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive">
                        <label class="form-check-label" for="isActive">Akun Aktif</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('form');

        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus pengguna ini?',
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
</script>
@endpush

@push('styles')
<style>
/* Perbaikan tampilan mobile */
@media (max-width: 768px) {
    .table th, .table td {
        white-space: nowrap;
    }

    .btn-group {
        display: flex;
        gap: 0.25rem;
    }

    .modal-dialog {
        margin: 0.5rem;
    }

    .card {
        margin-bottom: 1rem;
    }

    .form-label {
        margin-bottom: 0.25rem;
    }
}

/* Animasi smooth untuk interaksi */
.badge, .btn {
    transition: all 0.2s ease-in-out;
}

.btn:active {
    transform: scale(0.95);
}

/* Perbaikan tampilan tabel */
.table {
    font-size: 0.9rem;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

/* Perbaikan tampilan filter */
.form-select, .form-control {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

/* Perbaikan tampilan filter button */
.btn-check + .btn {
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 50px;
}

.btn-check:checked + .btn {
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-wrap: wrap;
    }

    .btn-check + .btn {
        width: auto;
        min-width: 80px;
        margin-bottom: 0.25rem;
    }
}

/* Hover effects */
.btn-check + .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-check:checked + .btn {
    transform: translateY(1px);
    box-shadow: none;
}
</style>
@endpush
