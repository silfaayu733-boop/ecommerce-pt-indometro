@extends('layouts.app')

@section('title', 'Daftar Akun Baru - PT. Indo Metro Surya Andola')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="p-3 bg-primary text-white rounded-circle d-inline-flex mb-3">
                        <i class="fa-solid fa-user-plus fs-4"></i>
                    </span>
                    <h3 class="fw-bold mb-1">Daftar Akun Pelanggan</h3>
                    <p class="text-muted small">Buat akun untuk mulai memesan bahan bangunan secara online</p>
                </div>

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="contoh: Budi Santoso" required>
                        @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-secondary">Alamat Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="budi@email.com" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold small text-secondary">Nomor WhatsApp / HP</label>
                            <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" value="{{ old('telepon') }}" placeholder="08xxxxxxxxxx" required>
                            @error('telepon')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Alamat Pengiriman (Wilayah Metro & Sekitarnya)</label>
                        <textarea name="alamat" rows="2" class="form-control @error('alamat') is-invalid @enderror" placeholder="Jl. Nama Jalan, No. Rumah, Kelurahan, Kecamatan, Kota Metro" required>{{ old('alamat') }}</textarea>
                        @error('alamat')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold small text-secondary">Kata Sandi</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter" required>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold small text-secondary">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm mb-3">
                        <i class="fa-solid fa-check me-1"></i> Selesaikan Pendaftaran
                    </button>

                    <div class="text-center small text-muted">
                        Sudah punya akun sebelumnya? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Masuk di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

