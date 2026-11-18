<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - PT. Indo Metro Surya Andola</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 4px 0; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 7px 10px; }
        th { background: #eee; text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .signatures { margin-top: 40px; width: 100%; border: none; }
        .signatures td { border: none; width: 50%; text-align: center; height: 70px; vertical-align: bottom; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #0284c7; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Dokumen Sekarang
        </button>
    </div>

    <div class="header">
        <h2>{{ $config->nama ?? 'PT. INDO METRO SURYA ANDOLA' }}</h2>
        <p>{{ $config->alamat ?? 'Jl. Jenderal Sudirman No. 88, Kota Metro, Lampung' }} | Telp: {{ $config->telp ?? '0821-7788-9900' }}</p>
        <h3 style="margin: 15px 0 5px 0;">LAPORAN REKAPITULASI PENJUALAN BAHAN BANGUNAN</h3>
        <p>Periode: <strong>{{ date('d/m/Y', strtotime($tglAwal)) }}</strong> s/d <strong>{{ date('d/m/Y', strtotime($tglAkhir)) }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 20%;">No. Invoice</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Pelanggan</th>
                <th style="width: 15%;">Metode Kirim</th>
                <th style="width: 20%;" class="text-right">Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $i => $row)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>#{{ $row->kode_invoice }}</td>
                    <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ $row->metode_pengiriman }}</td>
                    <td class="text-right">Rp {{ number_format($row->grandtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada transaksi.</td></tr>
            @endforelse
            <tr style="font-weight: bold; background: #f2f2f2;">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td></td>
            <td>
                Kota Metro, {{ date('d F Y') }}<br>
                Pimpinan / Pemilik Toko,<br><br><br><br>
                ( <strong>PT. Indo Metro Surya Andola</strong> )
            </td>
        </tr>
    </table>

</body>
</html>

