@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-2">Pesanan</h1>
    <p class="text-muted">Kelola semua pesanan pelanggan</p>

    <!-- Status Cards -->
    <div class="row g-3 mb-4">
        <!-- Card Menunggu -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-clock-history text-warning h1"></i>
                    <h5>Menunggu</h5>
                    <h3>{{ $pendingOrders }}</h3>
                </div>
            </div>
        </div>

        <!-- Card Diproses -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-gear text-info h1"></i>
                    <h5>Diproses</h5>
                    <h3>{{ $processingOrders }}</h3>
                </div>
            </div>
        </div>

        <!-- Card Selesai -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle text-success h1"></i>
                    <h5>Selesai</h5>
                    <h3>{{ $completedOrders }}</h3>
                </div>
            </div>
        </div>

        <!-- Card Dibatalkan -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle text-danger h1"></i>
                    <h5>Dibatalkan</h5>
                    <h3>{{ $cancelledOrders }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan ID, pelanggan, atau email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">Menunggu</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">Diproses</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-warning btn-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <!-- Tombol hapus pesanan -->
                                    <button class="btn btn-danger" onclick="confirmDelete('{{ $order->id }}')">Hapus</button>

                                    <!-- Form untuk menghapus pesanan -->
                                    <form id="delete-form-{{ $order->id }}" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:none;">
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

            {{ $orders->links() }}
        </div>
    </div>
</div>

<!-- Modal Edit untuk setiap pesanan -->
@foreach($orders as $order)
<div class="modal fade" id="editOrderModal{{ $order->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pesanan #{{ $order->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <!-- Detail Pesanan -->
                    <div class="table-responsive mb-3">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <input type="number" name="quantities[{{ $item->id }}]" value="{{ $item->quantity }}" min="1" class="form-control" required>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status Pesanan</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Tambahkan ini di dalam <head> di file view Anda -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script untuk SweetAlert2 -->
@push('scripts')
<script>
function confirmDelete(orderId) {
    Swal.fire({
        title: 'Hapus Pesanan?',
        text: "Apakah Anda yakin ingin menghapus pesanan ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Jika pengguna mengonfirmasi, kirim permintaan penghapusan
            document.getElementById('delete-form-' + orderId).submit();
        }
    });
}
</script>
@endpush
@endsection
