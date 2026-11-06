@extends('layouts.app')

@section('title', 'Masuk ke Akun - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 18px;">
                <div class="card-body">
                    <!-- Brand Icon & Title -->
                    <div class="text-center mb-4">
                        <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 mb-2" style="width: 48px; height: 48px; font-weight: 800; font-size: 1.3rem;">
                            AB
                        </span>
                        <h4 class="fw-bold mb-1">Selamat Datang Kembali</h4>
                        <p class="text-muted small">Masuk ke akun Anda untuk belanja dan memantau pesanan</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input id="email" type="email"
                                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}"
                                       required autocomplete="email" autofocus
                                       placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label fw-semibold mb-0">Kata Sandi</label>
                                @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                    <a class="text-primary text-decoration-none small" href="{{ route('password.request') }}">
                                        Lupa Sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="input-group mt-2">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input id="password" type="password"
                                       class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password"
                                       placeholder="••••••••">
                            </div>
                            @error('password')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted small" for="remember">
                                Ingat saya di perangkat ini
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top small text-muted">
                        Belum memiliki akun pelanggan?
                        <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none">
                            Daftar Akun Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
