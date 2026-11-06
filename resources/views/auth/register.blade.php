@extends('layouts.app')

@section('title', 'Daftar Akun Baru - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 18px;">
                <div class="card-body">
                    <!-- Title -->
                    <div class="text-center mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 mb-2" style="width: 48px; height: 48px; font-weight: 800; font-size: 1.3rem;">
                            AB
                        </span>
                        <h4 class="fw-bold mb-1">Pendaftaran Akun Baru</h4>
                        <p class="text-muted small">Buat akun untuk kemudahan pemesanan, lacak order, dan promo eksklusif</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input id="name" type="text"
                                       class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}"
                                       required autocomplete="name" autofocus
                                       placeholder="Nama lengkap Anda">
                            </div>
                            @error('name')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input id="email" type="email"
                                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}"
                                       required autocomplete="email"
                                       placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password"
                                           class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                           name="password" required autocomplete="new-password"
                                           placeholder="Minimal 8 karakter">
                                </div>
                                @error('password')
                                    <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label fw-semibold">Ulangi Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-shield-check"></i>
                                    </span>
                                    <input id="password-confirm" type="password"
                                           class="form-control border-start-0 ps-0"
                                           name="password_confirmation" required autocomplete="new-password"
                                           placeholder="Ulangi kata sandi">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm mb-3">
                            <i class="bi bi-person-check me-2"></i> Daftar Sebagai Pelanggan
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top small text-muted">
                        Sudah punya akun sebelumnya?
                        <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">
                            Masuk di Sini
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
