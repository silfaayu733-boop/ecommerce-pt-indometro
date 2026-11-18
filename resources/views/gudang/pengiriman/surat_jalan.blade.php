<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan Pengiriman - #{{ $order->kode_invoice }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #333; padding: 25px; }
        .header { border-bottom: 2px solid #000; padding-bottom: 12px; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        .header p { margin: 3px 0; font-size: 12px; color: #555; }
        .right-meta { text-align: right; float: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .data-table th, .data-table td { border: 1px solid #777; padding: 8px 10px; }
        .data-table th { background: #f2f2f2; text-align: left; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .signatures { margin-top: 50px; width: 100%; border: none; }
        .signatures td { border: none; width: 33.3%; text-align: center; height: 80px; vertical-align: bottom; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #0284c7; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Surat Jalan
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #64748b; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
            Tutup
        </button>
    </div>

    <div class="header">
        <div class="right-meta">
            <h2 style="color: #333;">SURAT JALAN GUDANG</h2>
            <div><strong>No. Surat:</strong> SJ-{{ $order->kode_invoice }}</div>
            <div><strong>Tanggal Muat:</strong> {{ date('d/m/Y') }}</div>
            <div><strong>Metode Kirim:</strong> {{ $order->metode_pengiriman }}</div>
        </div>
        <h2>{{ $config->nama ?? 'PT. INDO METRO SURYA ANDOLA' }}</h2>
        <p>BAGIAN LOGISTIK & PERGUDANGAN MATERIAL BANGUNAN</p>
        <p>{{ $config->alamat ?? 'Kota Metro, Lampung' }} | Telp: {{ $config->telp ?? '0821-7788-9900' }}</p>
        <div style="clear: both;"></div>
    </div>

    <div style="background: #fafafa; border: 1px solid #ddd; padding: 12px; margin-bottom: 15px;">
        <strong>Tujuan Pengantaran / Pembeli:</strong><br>
        Nama Penerima: <strong>{{ $order->nama }}</strong><br>
        Telepon / WA: <strong>{{ $order->no_hp }}</strong><br>
        Alamat Tujuan: <strong>{{ $order->alamat }}</strong><br>
        Catatan Lokasi: <em>{{ $order->catatan ?? '-' }}</em>
    </div>

    <p style="margin-top: 15px; margin-bottom: 5px;">Mohon diterima barang / muatan bahan bangunan berikut dalam kondisi baik dan lengkap:</p>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 20%;">Kode SKU</th>
                <th style="width: 50%;">Nama Bahan Bangunan & Spesifikasi</th>
                <th style="width: 15%;" class="text-center">Kuantitas</th>
                <th style="width: 10%;" class="text-center">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->invoiceLogs as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->produk->kode ?? '-' }}</td>
                    <td><strong>{{ $item->produk->nama ?? '-' }}</strong></td>
                    <td class="text-center" style="font-size: 14px; font-weight: bold;">{{ $item->qty }}</td>
                    <td class="text-center">{{ $item->produk->satuan ?? 'Pcs' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Petugas Gudang,<br><br><br><br>
                ( ______________________ )
            </td>
            <td>
                Sopir / Pengantar Armada,<br><br><br><br>
                ( ______________________ )
            </td>
            <td>
                Penerima Barang di Lokasi,<br><br><br><br>
                ( ______________________ )
            </td>
        </tr>
    </table>

</body>
</html>

