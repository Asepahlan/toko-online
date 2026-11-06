@php
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('title', 'Daftar Pesanan - ' . config('app.name'))

@section('content')
<div class="container">
    <h2>Pesanan Saya</h2>

    @if($orders->isEmpty())
        <div class="alert alert-info">
            Anda belum memiliki pesanan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th scope="col">ID Pesanan</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                                <a href="{{ route('orders.resend.confirmation', $order->id) }}"
                                   class="btn btn-sm btn-info ms-2" aria-label="Kirim Ulang Konfirmasi Pesanan #{{ $order->id }}">
                                    <i class="bi bi-whatsapp me-1"></i>Kirim Ulang
                                </a>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info">Diproses</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">Selesai</span>
                            @else
                                <span class="badge bg-danger">Dibatalkan</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}" aria-label="Lihat Detail Pesanan #{{ $order->id }}">
                                <i class="bi bi-eye"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal untuk setiap pesanan -->
        @foreach($orders as $order)
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Detail Pesanan #{{ $order->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total</td>
                                    <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
