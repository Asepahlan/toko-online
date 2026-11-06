@extends('layouts.app')

@section('title', 'Login - ' . config('app.name'))

@section('meta')
<meta name="description" content="Login ke akun {{ config('app.name') }} Anda untuk mulai berbelanja.">
<meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <h1 class="h3 mb-3">Selamat Datang Kembali</h1>
                        <p class="text-muted">Login untuk melanjutkan ke {{ config('app.name') }}</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="password" class="form-label mb-0">Password</label>
                                @if (\Illuminate\Support\Facades\Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                        Lupa Password?
                                    </a>
                                @endif
                            </div>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="remember"
                                       id="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-muted">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="text-decoration-none">
                                    Daftar Sekarang
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Social Login -->
            {{-- <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <p class="text-muted mb-0">Atau login dengan</p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-danger">
                            <i class="fab fa-google me-2"></i>Google
                        </a>
                        <a href="#" class="btn btn-outline-primary">
                            <i class="fab fa-facebook me-2"></i>Facebook
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection
