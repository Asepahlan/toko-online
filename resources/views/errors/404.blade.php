@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan - ' . config('app.name'))

@section('content')
<div class="container text-center py-5">
    <div class="mb-4">
        <i class="bi bi-exclamation-circle display-1 text-muted"></i>
    </div>
    <h1 class="display-4">404</h1>
    <h2 class="h4 mb-4">Halaman Tidak Ditemukan</h2>
    <p class="text-muted mb-4">
        Maaf, halaman yang Anda cari tidak ditemukan atau telah dihapus.
    </p>
    <div class="d-flex justify-content-center gap-2">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="bi bi-house"></i> Halaman Utama
        </a>
    </div>
</div>
@endsection
