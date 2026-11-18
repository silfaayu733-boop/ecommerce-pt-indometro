<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Penjualan - #{{ $order->kode_invoice }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            padding: 20px;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }
        .invoice-title {
            text-align: right;
            float: right;
        }
        .meta-table, .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .item-table th, .item-table td {
            border: 1px solid #ccc;
            padding: 8px 10px;
        }
        .item-table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row td {
            font-weight: bold;
        }
        .signatures {
            margin-top: 40px;
            width: 100%;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            height: 80px;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #0284c7; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
            Cetak Faktur Sekarang
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #64748b; color: #fff; border: none; border-radius: 4px; cursor: pointer;">
            Tutup
        </button>
    </div>

    <div class="header">
        <div class="invoice-title">
            <h2 style="color: #0284c7;">FAKTUR PENJUALAN</h2>
            <div><strong>No. Invoice:</strong> {{ $order->kode_invoice }}</div>
            <div><strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
            <div><strong>Status:</strong> {{ $order->status_label }}</div>
        </div>
        <h2>{{ $config->nama ?? 'PT. INDO METRO SURYA ANDOLA' }}</h2>
        <p>{{ $config->alamat ?? 'Jl. Jenderal Sudirman No. 88, Metro Pusat, Kota Metro, Lampung' }}</p>
        <p>Telepon: {{ $config->telp ?? '0821-7788-9900' }} | Email: {{ $config->email ?? 'indometro@gmail.com' }}</p>
        <div style="clear: both;"></div>
    </div>

    <table class="meta-table" style="margin-bottom: 15px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <strong>Tujuan Pengiriman / Pembeli:</strong><br>
                Nama: {{ $order->nama }}<br>
                Telepon: {{ $order->no_hp }}<br>
                Alamat: {{ $order->alamat }}
            </td>
            <td style="width: 50%; vertical-align: top;">
                <strong>Metode Pengiriman:</strong> {{ $order->metode_pengiriman }}<br>
                <strong>Metode Pembayaran:</strong> QRIS Digital (Tanpa Rekening)<br>
                @if($order->bukti)
                    <strong>Aplikasi Pembayar:</strong> {{ $order->bukti->bank }} ({{ $order->bukti->no_rek }})<br>
                @endif
                <strong>No. Resi / Pengantar:</strong> {{ $order->no_resi ?? '-' }}<br>
                <strong>Catatan:</strong> {{ $order->catatan ?? '-' }}
            </td>
        </tr>
    </table>

    <table class="item-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Kode SKU</th>
                <th style="width: 40%;">Nama Barang / Material</th>
                <th class="text-right" style="width: 15%;">Harga Satuan</th>
                <th class="text-center" style="width: 10%;">Jumlah</th>
                <th class="text-right" style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->invoiceLogs as $i => $item)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $item->produk->kode ?? '-' }}</td>
                    <td>{{ $item->produk->nama ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->qty }} {{ $item->produk->satuan ?? 'Pcs' }}</td>
                    <td class="text-right">Rp {{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="5" class="text-right">Subtotal Produk:</td>
                <td class="text-right">Rp {{ number_format($order->grandtotal - $order->ongkir, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="5" class="text-right">Biaya Pengiriman ({{ $order->metode_pengiriman }}):</td>
                <td class="text-right">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row" style="background-color: #f9f9f9;">
                <td colspan="5" class="text-right" style="font-size: 14px;">GRAND TOTAL:</td>
                <td class="text-right" style="font-size: 14px; color: #0284c7;">Rp {{ number_format($order->grandtotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Tanda Terima Pembeli,<br><br><br><br>
                ( ______________________ )
            </td>
            <td>
                Petugas Kasir / Toko,<br><br><br><br>
                ( ______________________ )
            </td>
        </tr>
    </table>

</body>
</html>

