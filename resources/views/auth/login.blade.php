@extends('layouts.app')

@section('title', 'Login Akun - PT. Indo Metro Surya Andola')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card overflow-hidden shadow-sm border-0">
                <div class="row g-0">
                    <!-- Sisi Kiri: Sesuai Sketsa Wireframe Halaman 74 Skripsi -->
                    <div class="col-md-5 bg-primary bg-gradient text-white p-5 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <span class="p-2 bg-white text-primary rounded-3">
                                    <i class="fa-solid fa-trowel-bricks fs-4"></i>
                                </span>
                                <h4 class="fw-bold mb-0 text-white">INDO METRO</h4>
                            </div>
                            <h3 class="fw-bold mb-3">Selamat Datang!</h3>
                            <p class="text-white-50">
                                Belanja berbagai kebutuhan bahan bangunan berkualitas tinggi langsung dari supplier terpercaya di Kota Metro.
                            </p>
                        </div>

                        <div class="mt-4 pt-4 border-top border-white border-opacity-25">
                            <p class="small text-white-50 mb-2">Belum memiliki akun toko online kami?</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-light w-100 py-2 rounded-pill fw-semibold">
                                <i class="fa-solid fa-user-plus me-1"></i> Buat Akun Baru
                            </a>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Form Login -->
                    <div class="col-md-7 p-5 bg-white">
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-1">Masuk ke Akun</h3>
                            <p class="text-muted small">Silakan masukkan email dan kata sandi Anda</p>
                        </div>

                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold small text-secondary">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="email" id="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh: nama@email.com" required autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold small text-secondary">Kata Sandi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="password" id="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label small text-secondary" for="remember">Ingat Saya</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Masuk Sekarang
                            </button>
                        </form>

                        <!-- Akun Demo untuk Pengujian Sidang/Dosen -->
                        <div class="mt-4 p-3 bg-light rounded-3 border">
                            <span class="badge bg-secondary mb-2">Akun Uji Coba Demo:</span>
                            <div class="small text-muted" style="font-size: 0.78rem;">
                                <div><strong>Owner/Admin:</strong> admin@indometro.com / admin123</div>
                                <div><strong>Kasir:</strong> kasir@indometro.com / kasir123</div>
                                <div><strong>Gudang:</strong> gudang@indometro.com / gudang123</div>
                                <div><strong>Customer:</strong> budi@gmail.com / customer123</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

