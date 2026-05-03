<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Stok - Cikampek Jajanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111;
            margin: 16px;
            line-height: 1.35;
        }

        .header {
            margin-bottom: 12px;
            border: 1px solid #f1d1d4;
            border-top: 4px solid #cf202c;
            border-radius: 8px;
            background: #fff8f8;
            padding: 10px;
            overflow: hidden;
        }

        .header-left {
            float: left;
            width: 16%;
            text-align: center;
        }

        .header-right {
            float: left;
            width: 84%;
            text-align: center;
        }

        .logo {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            border: 1px solid #f6c8cc;
            object-fit: cover;
            background: #fff;
            margin-top: 3px;
        }

        .header h1 {
            margin: 2px 0 0;
            font-size: 19px;
            color: #9f1d28;
        }

        .header p {
            margin: 3px 0;
            font-size: 12px;
            color: #374151;
        }

        .clearfix {
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        .legend-table {
            margin-bottom: 8px;
        }

        .legend-table td {
            border: 1px solid #f1d1d4;
            background: #fffafa;
            text-align: left;
            padding: 6px;
            font-size: 11px;
        }

        .legend-chip {
            display: inline-block;
            min-width: 42px;
            text-align: center;
            color: #fff;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            padding: 1px 8px;
            margin-right: 6px;
        }

        .chip-masuk {
            background: #16a34a;
        }

        .chip-keluar {
            background: #d97706;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 7px 6px;
            font-size: 11px;
        }

        th {
            background: #cf202c;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        td.center {
            text-align: center;
        }

        .jenis-label {
            display: inline-block;
            min-width: 44px;
            text-align: center;
            color: #fff;
            border-radius: 999px;
            padding: 1px 8px;
            font-size: 10px;
            font-weight: bold;
        }

        .jenis-masuk {
            background: #16a34a;
        }

        .jenis-keluar {
            background: #d97706;
        }

        .footer-total {
            margin-top: 10px;
            border: 1px solid #f1d1d4;
            border-radius: 8px;
            background: #fff8f8;
            padding: 8px 10px;
            font-size: 11px;
        }

        .footer-total strong {
            color: #8f1b24;
        }

        .footer-note {
            margin-top: 6px;
            font-size: 10px;
            color: #6b7280;
            text-align: right;
        }

        .section-title {
            margin: 14px 0 8px;
            padding: 6px 8px;
            background: #fff1f2;
            border-left: 4px solid #cf202c;
            color: #8f1b24;
            font-size: 12px;
            font-weight: bold;
        }

        .summary-table td {
            font-size: 10px;
            background: #fffafa;
        }

        .audit-table {
            table-layout: fixed;
        }

        .audit-table td,
        .audit-table th {
            vertical-align: top;
            word-break: break-word;
        }

        .audit-detail {
            font-size: 9px;
            line-height: 1.25;
            white-space: pre-line;
        }

        .summary-value {
            font-weight: bold;
            color: #8f1b24;
        }

        .compact-note {
            margin-top: 6px;
            font-size: 10px;
            color: #6b7280;
        }

        .hint {
            margin-top: 8px;
            font-size: 11px;
            color: #6b7280;
            text-align: right;
        }

        @media print {
            .hint {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
        $laporanCollection = collect($laporan ?? []);
        $totalHari = $laporanCollection->count();
        $totalCabang = $laporanCollection->sum('total_cabang');
        $totalKeluar = $laporanCollection->sum('total_barang_keluar');
        $totalKembali = $laporanCollection->sum('total_barang_kembali');
        $totalTerpakai = $laporanCollection->sum('total_barang_terpakai');
        $totalMasuk = $laporanCollection->sum('total_barang_masuk');
    @endphp

    <div class="header">
        <div class="header-left">
            @if(!empty($logoBase64))
                <img src="{{ $logoBase64 }}" alt="Logo Jajanan Cikampek" class="logo">
            @endif
        </div>
        <div class="header-right">
            <h1>Laporan Transaksi Stok</h1>
            <p><strong>Cikampek Jajanan</strong></p>
            <p>Periode: {{ $tanggalMulai ?: '-' }} s/d {{ $tanggalSelesai ?: '-' }}</p>
            <p>Tanggal Cetak Server: {{ now()->format('d-m-Y H:i:s') }} WIB</p>
        </div>
        <div class="clearfix"></div>
    </div>

    <table class="legend-table">
        <tr>
            <td>
                <span class="legend-chip chip-masuk">MASUK</span>
                Transaksi penambahan stok
            </td>
            <td>
                <span class="legend-chip chip-keluar">KELUAR</span>
                Transaksi pengurangan stok
            </td>
        </tr>
    </table>

    <table class="legend-table summary-table">
        <tr>
            <td>Total Hari: <span class="summary-value">{{ $totalHari }}</span></td>
            <td>Total Cabang: <span class="summary-value">{{ $totalCabang }}</span></td>
            <td>Total Keluar: <span class="summary-value">{{ $totalKeluar }}</span></td>
            <td>Total Kembali: <span class="summary-value">{{ $totalKembali }}</span></td>
        </tr>
    </table>

    <table class="legend-table summary-table" style="margin-top: -6px;">
        <tr>
            <td>Total Terpakai: <span class="summary-value">{{ $totalTerpakai }}</span></td>
            <td>Total Barang Masuk: <span class="summary-value">{{ $totalMasuk }}</span></td>
            <td>Stok Real Saat Ini: <span class="summary-value">{{ $stokRealTotal ?? 0 }}</span></td>
            <td>Keterangan: keluar = dibawa ke cabang, kembali = sisa, terpakai = yang benar-benar habis.</td>
        </tr>
    </table>

    <div class="section-title">Laporan Stok Barang</div>
    <table class="audit-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 11%;">Tanggal</th>
                <th style="width: 8%;">Cabang</th>
                <th style="width: 10%;">Keluar / Bawa</th>
                <th style="width: 10%;">Kembali / Sisa</th>
                <th style="width: 8%;">Terpakai</th>
                <th style="width: 10%;">Barang Masuk</th>
                <th style="width: 10%;">Saldo Harian</th>
                <th style="width: 10%;">Stok Real</th>
                <th style="width: 18%;">Detail Cabang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanCollection as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td class="center">{{ $item['tanggal'] }}</td>
                    <td class="center">{{ $item['total_cabang'] }}</td>
                    <td class="center">{{ $item['total_barang_keluar'] }}</td>
                    <td class="center">{{ $item['total_barang_kembali'] }}</td>
                    <td class="center">{{ $item['total_barang_terpakai'] }}</td>
                    <td class="center">{{ $item['total_barang_masuk'] }}</td>
                    <td class="center">{{ $item['saldo_harian'] }}</td>
                    <td class="center">{{ $item['stok_real_saat_ini'] }}</td>
                    <td class="audit-detail">{{ str_replace("\n", "\n", $item['detail_cabang']) }}</td>
                </tr>
                @if(!empty($item['detail_barang']))
                    @foreach($item['detail_barang'] as $barang)
                        <tr style="background: #f8f8f8;">
                            <td colspan="10" style="padding: 8px 10px; font-size: 10px;">
                                <div style="border-left: 2px solid #cf202c; padding-left: 8px; margin-left: 2px;">
                                    <strong>{{ $barang['kode_barang'] }} - {{ $barang['nama_barang'] }}</strong>
                                    <div style="margin-top: 3px; line-height: 1.4;">
                                        @foreach($barang['per_cabang'] as $cabang)
                                            {{ $cabang['kode_cabang'] }}: keluar {{ $cabang['jumlah_bawa'] }}, kembali {{ $cabang['jumlah_sisa'] }}, terpakai {{ $cabang['jumlah_terpakai'] }} <br>
                                        @endforeach
                                        <div style="border-top: 1px solid #ddd; margin-top: 4px; padding-top: 4px; font-weight: bold;">
                                            Total: keluar {{ $barang['total_bawa'] }}, kembali {{ $barang['total_sisa'] }}, terpakai {{ $barang['total_terpakai'] }}, masuk {{ $barang['barang_masuk'] }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            @empty
                <tr>
                    <td colspan="10" class="center">Tidak ada data stok pada periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="compact-note">Catatan: keluar = stok yang dibawa ke cabang, kembali = sisa yang dibalikin, terpakai = yang habis dipakai, dan barang masuk adalah pembelian/penambahan stok.</div>

    <div class="footer-note">Dokumen audit trail. Data stok, transaksi, dan detail cabang diambil dari server.</div>

    <div class="hint">Laporan berhasil dibuat dan siap diunduh.</div>


</body>
</html>
