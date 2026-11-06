@extends('layouts.app')

@section('title', 'Daftar - ' . config('app.name'))

@section('meta')
<meta name="description" content="Daftar akun baru di {{ config('app.name') }} untuk mulai berbelanja.">
<meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <h1 class="h3 mb-3">Daftar Akun Baru</h1>
                        <p class="text-muted">Bergabung dengan {{ config('app.name') }}</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
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
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror"
                                       type="checkbox"
                                       name="terms"
                                       id="terms"
                                       required>
                                <label class="form-check-label" for="terms">
                                    Saya setuju dengan <a href="#" class="text-decoration-none">Syarat & Ketentuan</a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Daftar
                            </button>
                        </div>

                        <div class="text-center">
                            <p class="text-muted">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    Login
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
