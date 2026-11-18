@extends('layouts.app')

@section('title', 'Kontak & Lokasi Toko - PT. Indo Metro Surya Andola')

@section('content')
<div class="container-fluid px-3 px-lg-5 py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2">Informasi Kontak & Lokasi Toko</h2>
        <p class="text-muted">Kunjungi toko fisik kami atau hubungi kami untuk konsultasi kebutuhan material proyek bangunan</p>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="card h-100 border-0 bg-white p-4 shadow-sm">
                <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex mb-3 align-self-start">
                    <i class="fa-solid fa-location-dot fs-3"></i>
                </div>
                <h5 class="fw-bold mb-2">Alamat Toko</h5>
                <p class="text-secondary mb-3">{{ $config->alamat ?? 'Kota Metro, Lampung' }}</p>
                <div class="small text-muted"><i class="fa-solid fa-clock me-1"></i> Buka Setiap Hari: 07.30 - 17.00 WIB</div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 border-0 bg-white p-4 shadow-sm">
                <div class="p-3 bg-success bg-opacity-10 text-success rounded-circle d-inline-flex mb-3 align-self-start">
                    <i class="fa-solid fa-phone fs-3"></i>
                </div>
                <h5 class="fw-bold mb-2">Kontak & WhatsApp</h5>
                <p class="text-secondary mb-3">{{ $config->telp ?? '0821-7788-9900' }}</p>
                <div class="small text-muted mb-3"><i class="fa-solid fa-envelope me-1"></i> {{ $config->email ?? 'indometro@gmail.com' }}</div>
                <a href="https://wa.me/6282177889900" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 mt-auto">
                    <i class="fa-brands fa-whatsapp me-1"></i> Chat WhatsApp
                </a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100 border-0 bg-white p-4 shadow-sm text-center">
                <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex mb-3 align-self-center">
                    <i class="fa-solid fa-qrcode fs-3"></i>
                </div>
                <h5 class="fw-bold mb-2">Pembayaran Digital QRIS</h5>
                <div class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 mb-3 align-self-center">
                    <i class="fa-solid fa-bolt me-1"></i> Tanpa Perlu Rekening Bank
                </div>
                <div class="p-2 bg-light rounded-3 border mb-3">
                    <img src="{{ asset('uploads/' . ($config->qris ?? 'qris_indometro.svg')) }}" alt="QRIS Toko" class="img-fluid rounded border" style="max-height: 160px;">
                </div>
                <div class="fw-bold text-dark small mb-1">NMID : ID1024398711090</div>
                <p class="small text-muted mb-0">Scan langsung dari <strong>DANA, GoPay, OVO, ShopeePay, LinkAja</strong> atau m-banking apa saja.</p>
            </div>
        </div>
    </div>

    <!-- Peta Google Maps Embed -->
    <div class="card border-0 shadow-sm overflow-hidden bg-white p-3">
        <h5 class="fw-bold mb-3"><i class="fa-solid fa-map me-2 text-primary"></i> Peta Lokasi Toko</h5>
        <div class="rounded-3 overflow-hidden">
            @if($config && $config->map)
                {!! $config->map !!}
            @else
                <iframe src="https://maps.google.com/maps?q=Kota+Metro+Lampung&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            @endif
        </div>
    </div>
</div>
@endsection

