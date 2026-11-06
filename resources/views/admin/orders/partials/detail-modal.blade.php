{{-- <!-- Modal Detail Pesanan -->
<div class="modal fade" id="orderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailContent">
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
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
window.viewOrder = function(orderId) {
    $.ajax({
        url: `/admin/orders/${orderId}`,
        type: 'GET',
        beforeSend: function() {
            $('#orderDetailContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                </div>
            `);
            $('#orderDetailModal').modal('show');
        },
        success: function(response) {
            if (response.success) {
                let order = response.order;
                $('#orderDetailContent').html(`
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Informasi Pelanggan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Nama</label>
                                    <p class="mb-0">${order.user.name}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Email</label>
                                    <p class="mb-0">${order.user.email}</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    ${order.order_items.map(item => `
                                        <tr>
                                            <td>${item.product.name}</td>
                                            <td class="text-end">Rp ${formatNumber(item.price)}</td>
                                            <td class="text-center">${item.quantity}</td>
                                            <td class="text-end">Rp ${formatNumber(item.price * item.quantity)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total</td>
                                        <td class="text-end fw-bold">Rp ${formatNumber(order.total)}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status Pesanan</label>
                        <select name="status" id="status" class="form-select">
                            <option value="pending" ${order.status == 'pending' ? 'selected' : ''}>Menunggu</option>
                            <option value="processing" ${order.status == 'processing' ? 'selected' : ''}>Diproses</option>
                            <option value="completed" ${order.status == 'completed' ? 'selected' : ''}>Selesai</option>
                            <option value="cancelled" ${order.status == 'cancelled' ? 'selected' : ''}>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">Catatan</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Tambahkan catatan...">${order.notes}</textarea>
                    </div>
                `);
            }
        },
        error: function() {
            $('#orderDetailContent').html(`
                <div class="text-center py-4 text-danger">
                    <i class="bi bi-exclamation-circle h1"></i>
                    <p class="mb-0 mt-2">Gagal memuat detail pesanan</p>
                </div>
            `);
        }
    });
}

function formatNumber(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}
</script>
@endpush --}}
