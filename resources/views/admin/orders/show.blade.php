{{-- @extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Detail Pesanan #{{ $order->id }}</h4>

            <div class="modal-body">
                <!-- Informasi Pelanggan -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Nama</label>
                                <p class="mb-0">{{ $order->user->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p class="mb-0">{{ $order->user->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Produk -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-3">Detail Produk</h6>
                    <div class="table-responsive">
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
                                    <td class="text-center">{{ $item->quantity }}</td>
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
                </div>

                <!-- Status Pesanan -->
                <div class="mb-3">
                    <label for="status" class="form-label fw-bold">Status Pesanan</label>
                    <select name="status" id="status" class="form-select">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>

            {{-- Tombol Update Status --}}
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>

                <div class="btn-group">
                    <form action="{{ route('admin.orders.update.status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>
                </div>

                <!-- Tombol Edit -->
                <button type="button" class="btn btn-warning btn-sm rounded-circle" data-bs-toggle="modal" data-bs-target="#editOrderModal{{ $order->id }}">
                    <i class="bi bi-pencil"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection --}}
