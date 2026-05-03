<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Barang Harian - Cikampek Jajanan</title>
    <style>
        @page {
            margin: 12px 14px;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111827;
            margin: 0;
            line-height: 1.28;
            font-size: 10px;
            background: #ffffff;
        }

        .page {
            padding: 14px;
        }

        .header {
            margin-bottom: 10px;
            border: 1px solid #dbe4ff;
            border-top: 5px solid #1d4ed8;
            border-radius: 10px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
            padding: 10px 12px;
            overflow: hidden;
        }

        .header-left {
            float: left;
            width: 11%;
            text-align: center;
        }

        .header-right {
            float: left;
            width: 89%;
            text-align: center;
        }

        .logo {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            border: 1px solid #dbe4ff;
            object-fit: cover;
            background: #fff;
            margin-top: 2px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #1e3a8a;
            letter-spacing: 0.2px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #4b5563;
        }

        .clearfix {
            clear: both;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-grid {
            margin: 8px 0 10px;
        }

        .meta-grid td {
            border: 1px solid #d7e3ff;
            background: #f7fbff;
            padding: 7px 8px;
            font-size: 10px;
            border-radius: 6px;
        }

        .meta-label {
            display: block;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-size: 8px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .meta-value {
            font-size: 12px;
            font-weight: bold;
            color: #0f172a;
        }

        .meta-value--blue {
            color: #1d4ed8;
        }

        .meta-value--gold {
            color: #b45309;
        }

        .meta-value--red {
            color: #b91c1c;
        }

        .section-card {
            margin-top: 10px;
            border: 1px solid #d7e3ff;
            border-radius: 10px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .section-head {
            display: table;
            width: 100%;
            background: linear-gradient(135deg, #f8fbff 0%, #ffffff 100%);
            border-bottom: 1px solid #d7e3ff;
        }

        .section-head-left,
        .section-head-right {
            display: table-cell;
            vertical-align: middle;
            padding: 8px 10px;
        }

        .section-head-left {
            width: 68%;
        }

        .section-head-right {
            width: 32%;
            text-align: right;
        }

        .section-eyebrow {
            font-size: 8px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #1d4ed8;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .section-title {
            margin: 0;
            font-size: 15px;
            color: #0f172a;
        }

        .section-subtitle {
            margin-top: 3px;
            font-size: 10px;
            color: #64748b;
        }

        .section-chip {
            display: inline-block;
            margin-left: 6px;
            padding: 4px 8px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .section-chip--blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .section-chip--gold {
            background: #fef3c7;
            color: #b45309;
        }

        .section-chip--red {
            background: #fee2e2;
            color: #b91c1c;
        }

        .section-summary {
            padding: 8px 10px 10px;
            background: #ffffff;
        }

        .section-summary table td {
            border: 1px solid #e5e7eb;
            background: #fbfdff;
            padding: 6px 7px;
            font-size: 9px;
        }

        .summary-label {
            display: block;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-size: 7px;
            margin-bottom: 2px;
        }

        .summary-value {
            font-size: 11px;
            font-weight: bold;
            color: #0f172a;
        }

        .extended-table {
            table-layout: fixed;
            width: 100%;
        }

        .extended-table th,
        .extended-table td {
            border: 1px solid #cfd8e3;
            padding: 6px 6px;
            font-size: 9px;
            text-align: center;
            vertical-align: middle;
            word-break: break-word;
            line-height: 1.1;
        }

        .extended-table thead th {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            font-size: 9px;
        }

        .zone-left-head {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
        }

        .zone-right-head {
            background: linear-gradient(135deg, #b91c1c 0%, #b45309 100%);
            color: #ffffff;
        }

        .cabang-head {
            background: #e8f0ff;
            color: #1d4ed8;
            font-size: 8px;
            line-height: 1.2;
        }

        .cabang-head span {
            display: block;
            color: #475569;
            font-size: 7px;
            margin-top: 1px;
        }

        .sub-head {
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            color: #0f172a;
        }

        .sub-bawa {
            background: #e0f2fe;
        }

        .sub-sisa {
            background: #fef3c7;
        }

        .sub-pakai {
            background: #ffe4e6;
            font-weight: bold;
        }

        .zone-right-sub {
            background: #fff4e8;
            color: #9a3412;
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: 0.35px;
        }

        .item-column {
            text-align: left !important;
            background: #ffffff;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 180px;
            padding-left: 10px;
        }

        .item-column small {
            display: block;
            margin-top: 1px;
            font-weight: normal;
            color: #64748b;
        }

        .no-column {
            background: #f8fbff;
            font-weight: 700;
            width: 32px;
            padding-left: 8px;
            padding-right: 8px;
        }

        /* zebra stripes for readability */
        .extended-table tbody tr:nth-child(odd) {
            background: #ffffff;
        }

        .extended-table tbody tr:nth-child(even) {
            background: #fbfdff;
        }

        /* tighten header cell padding for cabang blocks */
        .cabang-head,
        .zone-right-head,
        .zone-left-head {
            padding: 6px 6px;
        }

        /* smaller subheader text to reduce clutter */
        .sub-head {
            font-size: 7px;
            padding: 4px 3px;
        }

        .mutasi-total {
            background: #fffaf4;
            color: #7c2d12;
            font-weight: bold;
        }

        .mutasi-terpakai {
            background: #fff1e6;
            color: #9a3412;
            font-weight: bold;
        }

        .mutasi-masuk {
            background: #f0fdf4;
            color: #166534;
            font-weight: bold;
        }

        .stok-real-cell {
            background: #eff6ff;
            color: #1e3a8a;
            font-weight: bold;
        }

        .cabang-blank {
            color: #94a3b8;
        }

        .barang-name {
            line-height: 1.2;
        }

        .barang-code {
            display: block;
            font-size: 7px;
            color: #64748b;
            font-weight: normal;
            margin-top: 1px;
        }

        .footnote {
            margin-top: 8px;
            font-size: 9px;
            color: #64748b;
            text-align: right;
        }

        .compact-note {
            margin-top: 5px;
            font-size: 9px;
            color: #64748b;
        }

        .hint {
            margin-top: 8px;
            font-size: 10px;
            color: #64748b;
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

    <div class="page">
        <div class="header">
            <div class="header-left">
                @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" alt="Logo Jajanan Cikampek" class="logo">
                @endif
            </div>
            <div class="header-right">
                <h1>Laporan Stok Barang Harian</h1>
                <p><strong>Cikampek Jajanan</strong></p>
                <p>Periode: {{ $tanggalMulai ?: '-' }} s/d {{ $tanggalSelesai ?: '-' }} | Cetak: {{ now()->format('d-m-Y H:i:s') }} WIB</p>
                <p>Format: Horizontal Extended Table untuk audit cabang dan ringkasan mutasi umum</p>
            </div>
            <div class="clearfix"></div>
        </div>

        <table class="meta-grid">
            <tr>
                <td>
                    <span class="meta-label">Total Hari</span>
                    <span class="meta-value meta-value--blue">{{ $totalHari }}</span>
                </td>
                <td>
                    <span class="meta-label">Total Cabang Tercatat</span>
                    <span class="meta-value meta-value--blue">{{ $totalCabang }}</span>
                </td>
                <td>
                    <span class="meta-label">Total Keluar / Bawa</span>
                    <span class="meta-value meta-value--red">{{ $totalKeluar }}</span>
                </td>
                <td>
                    <span class="meta-label">Total Kembali / Sisa</span>
                    <span class="meta-value meta-value--gold">{{ $totalKembali }}</span>
                </td>
                <td>
                    <span class="meta-label">Total Terpakai</span>
                    <span class="meta-value meta-value--red">{{ $totalTerpakai }}</span>
                </td>
                <td>
                    <span class="meta-label">Total Barang Masuk</span>
                    <span class="meta-value meta-value--blue">{{ $totalMasuk }}</span>
                </td>
                <td>
                    <span class="meta-label">Stok Real Sistem</span>
                    <span class="meta-value meta-value--gold">{{ $stokRealTotal ?? 0 }}</span>
                </td>
            </tr>
        </table>

        @forelse($laporanCollection as $item)
            @php
                $cabangHeaders = collect($item['cabang_headers'] ?? [])->take(10)->values();

                if ($cabangHeaders->isEmpty()) {
                    $cabangHeaders = collect($item['detail_barang'] ?? [])
                        ->flatMap(function ($barang) {
                            return collect($barang['per_cabang'] ?? [])->map(function ($cabang) {
                                return [
                                    'cabang_id' => $cabang['cabang_id'] ?? null,
                                    'kode_cabang' => $cabang['kode_cabang'] ?? '-',
                                    'nama_cabang' => $cabang['nama_cabang'] ?? '-',
                                ];
                            });
                        })
                        ->unique('cabang_id')
                        ->values();
                }

                if ($cabangHeaders->isEmpty()) {
                    $cabangHeaders = collect([
                        [
                            'cabang_id' => null,
                            'kode_cabang' => '-',
                            'nama_cabang' => 'Tidak Ada Cabang',
                        ],
                    ]);
                }

                $cabangHeaders = $cabangHeaders->take(10)->values();
                $cabangCount = $cabangHeaders->count();
                $detailBarang = collect($item['detail_barang'] ?? []);
                $cabangChunks = $cabangHeaders->chunk(5);
            @endphp
            @foreach($cabangChunks as $chunkIndex => $chunkHeaders)
            <div class="section-card" style="{{ $loop->last ? '' : 'page-break-after: always;' }}">
                <div class="section-head">
                    <div class="section-head-left">
                        <div class="section-eyebrow">Laporan Stok Harian</div>
                        <div class="section-title">{{ $item['tanggal'] }}</div>
                        <div class="section-subtitle">
                            Audit cabang per barang untuk satu hari transaksi.
                            <span class="section-chip section-chip--blue">{{ $item['total_cabang'] }} cabang</span>
                            <span class="section-chip section-chip--gold">Masuk {{ $item['total_barang_masuk'] }}</span>
                            <span class="section-chip section-chip--red">Terpakai {{ $item['total_barang_terpakai'] }}</span>
                            <span class="section-chip" style="background:#eef2ff;color:#1d4ed8;">Halaman {{ $chunkIndex+1 }} / {{ $cabangChunks->count() }}</span>
                        </div>
                    </div>
                    <div class="section-head-right">
                        <div class="summary-label">Ringkasan Hari Ini</div>
                        <div class="summary-value">Keluar {{ $item['total_barang_keluar'] }}</div>
                        <div class="summary-value">Kembali {{ $item['total_barang_kembali'] }}</div>
                        <div class="summary-value">Stok Real {{ $item['stok_real_saat_ini'] }}</div>
                    </div>
                </div>

                <div class="section-summary">
                    <table>
                        <tr>
                            <td>
                                <span class="summary-label">Saldo Harian</span>
                                <span class="summary-value">{{ $item['saldo_harian'] }}</span>
                            </td>
                            <td>
                                <span class="summary-label">Total Barang Masuk</span>
                                <span class="summary-value">{{ $item['total_barang_masuk'] }}</span>
                            </td>
                            <td>
                                <span class="summary-label">Total Keluar / Bawa</span>
                                <span class="summary-value">{{ $item['total_barang_keluar'] }}</span>
                            </td>
                            <td>
                                <span class="summary-label">Total Kembali / Sisa</span>
                                <span class="summary-value">{{ $item['total_barang_kembali'] }}</span>
                            </td>
                            <td>
                                <span class="summary-label">Total Terpakai</span>
                                <span class="summary-value">{{ $item['total_barang_terpakai'] }}</span>
                            </td>
                            <td>
                                <span class="summary-label">Stok Real</span>
                                <span class="summary-value">{{ $item['stok_real_saat_ini'] }}</span>
                            </td>
                        </tr>
                    </table>
                </div>

                <table class="extended-table">
                    <thead>
                        <tr>
                            <th rowspan="3" class="no-column zone-left-head" style="width: 34px;">No.</th>
                            <th rowspan="3" class="item-column zone-left-head" style="width: 160px;">Nama Barang</th>
                            <th colspan="{{ $chunkHeaders->count() * 3 }}" class="zone-left-head">Zona Kiri - Audit Absensi Barang</th>
                            <th colspan="5" class="zone-right-head">Zona Kanan - Ringkasan Mutasi Umum</th>
                        </tr>
                        <tr>
                            @foreach($chunkHeaders as $cabang)
                                <th colspan="3" class="cabang-head">
                                    {{ $cabang['kode_cabang'] }}
                                    <span>{{ $cabang['nama_cabang'] }}</span>
                                </th>
                            @endforeach
                            <th colspan="5" class="zone-right-head">Mutasi Umum</th>
                        </tr>
                        <tr>
                            @foreach($chunkHeaders as $cabang)
                                <th class="sub-head sub-bawa">Bawa</th>
                                <th class="sub-head sub-sisa">Sisa</th>
                                <th class="sub-head sub-pakai">Pakai</th>
                            @endforeach
                            <th class="zone-right-sub">Total Keluar</th>
                            <th class="zone-right-sub">Total Kembali</th>
                            <th class="zone-right-sub">Terpakai</th>
                            <th class="zone-right-sub">Barang Masuk</th>
                            <th class="zone-right-sub">Stok Real</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailBarang as $barang)
                            <tr>
                                <td class="no-column">{{ $loop->iteration }}</td>
                                <td class="item-column">
                                    <div class="barang-name">
                                        {{ $barang['nama_barang'] }}
                                        <span class="barang-code">{{ $barang['kode_barang'] }}</span>
                                    </div>
                                </td>

                                @foreach($chunkHeaders as $cabang)
                                    @php
                                        $cabangData = data_get($barang['per_cabang_map'] ?? [], $cabang['cabang_id']);
                                    @endphp
                                    <td class="{{ $cabangData ? '' : 'cabang-blank' }}">{{ $cabangData['jumlah_bawa'] ?? '-' }}</td>
                                    <td class="{{ $cabangData ? '' : 'cabang-blank' }}">{{ $cabangData['jumlah_sisa'] ?? '-' }}</td>
                                    <td class="mutasi-terpakai {{ $cabangData ? '' : 'cabang-blank' }}">{{ $cabangData['jumlah_terpakai'] ?? '-' }}</td>
                                @endforeach

                                <td class="mutasi-total">{{ $barang['total_bawa'] ?? 0 }}</td>
                                <td class="mutasi-total">{{ $barang['total_sisa'] ?? 0 }}</td>
                                <td class="mutasi-terpakai">{{ $barang['total_terpakai'] ?? 0 }}</td>
                                <td class="mutasi-masuk">{{ $barang['barang_masuk'] ?? 0 }}</td>
                                <td class="stok-real-cell">{{ $barang['stok_real'] ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 7 + ($chunkHeaders->count() * 3) }}" style="padding: 14px; font-size: 10px; color: #64748b; text-align: center;">
                                    Tidak ada data barang pada tanggal ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="section-summary" style="padding-top: 6px;">
                    <div class="compact-note">
                        Rumus utama: <strong>Stok Real</strong> = (Stok Awal + Barang Masuk) - Terpakai. Kolom Terpakai di setiap barang dibuat bold untuk menonjolkan pemakaian lapangan.
                    </div>
                </div>
            </div>
            @endforeach
        @empty
            <div class="section-card">
                <div class="section-summary" style="text-align: center; padding: 18px 12px; color: #64748b;">
                    Tidak ada data stok pada periode ini.
                </div>
            </div>
        @endforelse

        <div class="footnote">Dokumen audit trail. Data stok, distribusi cabang, dan barang masuk diambil dari server.</div>
        <div class="hint">Laporan berhasil dibuat dan siap diunduh.</div>
    </div>
</body>
</html>
