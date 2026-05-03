@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok')

@section('content')
    @php
        $laporanCollection = collect($laporan);
        $totalBarang = $laporanCollection->count();
        $totalMasuk = $laporanCollection->sum('barang_masuk');
        $totalKeluar = $laporanCollection->sum('barang_keluar');
        $totalStokAkhir = $laporanCollection->sum('stok_akhir');
        $totalStokSaatIni = $laporanCollection->sum('stok_saat_ini');
        $totalBalance = $laporanCollection->sum('balance');
    @endphp

    <div class="print-only-header">
        <h2>Laporan Stok Barang - Cikampek Jajanan</h2>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Stok Barang</h3>
                    <div class="card-tools">
                        <a href="{{ route('laporan.index', array_filter(['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'export' => 'excel'])) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('laporan.index', array_filter(['tanggal_mulai' => $tanggalMulai, 'tanggal_selesai' => $tanggalSelesai, 'export' => 'pdf'])) }}" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="laporan-insights">
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-cubes"></i> Total Barang</div>
                            <div class="laporan-insight__value">{{ $totalBarang }}</div>
                        </div>
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-arrow-down"></i> Total Masuk</div>
                            <div class="laporan-insight__value">{{ $totalMasuk }}</div>
                        </div>
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-arrow-up"></i> Total Keluar</div>
                            <div class="laporan-insight__value">{{ $totalKeluar }}</div>
                        </div>
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-boxes"></i> Total Stok Saat Ini</div>
                            <div class="laporan-insight__value">{{ $totalStokSaatIni }}</div>
                        </div>
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-scale-balanced"></i> Total Balance</div>
                            <div class="laporan-insight__value">{{ $totalBalance }}</div>
                        </div>
                        <div class="laporan-insight">
                            <div class="laporan-insight__label"><i class="fas fa-boxes"></i> Total Stok Akhir</div>
                            <div class="laporan-insight__value">{{ $totalStokAkhir }}</div>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('laporan.index') }}" class="row g-2 mb-3 laporan-filter">
                        <div class="col-md-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                        </div>
                        <div class="col-md-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-rotate-left"></i> Reset
                            </a>
                        </div>
                    </form>

                    <div class="laporan-meta">
                        <span class="laporan-chip"><i class="fas fa-database"></i> Data: {{ $totalBarang }} barang</span>
                        <span class="laporan-chip"><i class="fas fa-clock"></i> Sinkron: {{ now()->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div class="table-responsive laporan-table">
                    <table class="table table-bordered table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 8%">Kode Barang</th>
                                <th style="width: 13%">Nama Barang</th>
                                <th style="width: 9%">Stok Saat Ini/Awal</th>
                                <th style="width: 8%">Barang Masuk</th>
                                <th style="width: 8%">Barang Keluar</th>
                                <th style="width: 9%">Stok Akhir</th>
                                <th style="width: 8%">Balance</th>
                                <th>Detail Cabang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($laporan) > 0)
                                @foreach($laporan as $item)
                                    <tr>
                                        <td><span class="laporan-no">{{ $loop->iteration }}</span></td>
                                        <td>{{ $item['kode_barang'] }}</td>
                                        <td>{{ $item['nama_barang'] }}</td>
                                        <td class="text-center"><span class="badge laporan-badge laporan-badge--muted">{{ $item['stok_awal'] }}</span></td>
                                        <td class="text-center">
                                            <span class="badge laporan-badge laporan-badge--masuk">{{ $item['barang_masuk'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge laporan-badge laporan-badge--keluar">{{ $item['barang_keluar'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge laporan-badge laporan-badge--akhir">{{ $item['stok_akhir'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge laporan-badge {{ $item['balance'] == 0 ? 'laporan-badge--masuk' : 'laporan-badge--keluar' }}">{{ $item['balance'] }}</span>
                                        </td>
                                        <td class="detail-cabang-cell">{{ $item['detail_cabang'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        :root {
            --primary-red: #c62833;
            --red-light: #cf202c;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 6px 16px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 20px 48px rgba(0, 0, 0, 0.2);
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .print-only-header {
            display: none;
        }

        .laporan-insights {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.2rem;
            margin-bottom: 1.6rem;
        }

        .laporan-insight {
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border: 1px solid rgba(198, 40, 51, 0.08);
            border-radius: 1.2rem;
            padding: 1.2rem;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            animation: slideInDown 0.6s ease forwards;
        }

        .laporan-insight::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #cf202c 0%, #c62833 100%);
        }

        .laporan-insight:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .laporan-insight__label {
            font-size: 0.8rem;
            color: #9f1d28;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .laporan-insight__value {
            color: #2d2d2d;
            font-size: 1.4rem;
            font-weight: 950;
            line-height: 1.2;
        }

        .laporan-filter {
            border: 1px solid rgba(198, 40, 51, 0.1);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 248, 248, 0.98) 100%);
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: var(--shadow-md);
        }

        .laporan-filter .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #2d2d2d;
            margin-bottom: 0.5rem;
        }

        .laporan-filter .form-control,
        .laporan-filter .form-select {
            border: 1px solid rgba(198, 40, 51, 0.12);
            border-radius: 0.6rem;
            padding: 0.6rem 0.8rem;
            transition: var(--transition);
        }

        .laporan-filter .form-control:focus,
        .laporan-filter .form-select:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(198, 40, 51, 0.1);
        }

        .laporan-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }

        .laporan-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid rgba(198, 40, 51, 0.12);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 248, 248, 0.95) 100%);
            color: #8f1b24;
            border-radius: 999px;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: var(--shadow-sm);
        }

        .laporan-chip:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .laporan-table {
            border-radius: 1.2rem;
            overflow: hidden;
            border: none;
            box-shadow: var(--shadow-lg);
            background: #fff;
        }

        .laporan-table table {
            table-layout: fixed;
            min-width: 1400px;
        }

        .laporan-table th,
        .laporan-table td {
            vertical-align: top;
        }

        .laporan-table .table thead th {
            background: linear-gradient(135deg, #cf202c 0%, #c62833 100%);
            color: #fff;
            border: none;
            font-weight: 850;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.8rem;
        }

        .laporan-table .table tbody tr {
            transition: var(--transition);
            border-bottom: 1px solid rgba(198, 40, 51, 0.06);
        }

        .laporan-table .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(206, 32, 44, 0.04) 0%, rgba(206, 32, 44, 0.02) 100%);
            transform: translateX(3px);
        }

        .laporan-no {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2rem;
            height: 2rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #cf202c 0%, #c62833 100%);
            color: #fff;
            font-weight: 800;
            font-size: 0.8rem;
            box-shadow: var(--shadow-sm);
        }

        .laporan-badge {
            color: #fff;
            border: 1px solid transparent;
            box-shadow: var(--shadow-md);
            font-weight: 700;
            padding: 0.4rem 0.8rem;
        }

        .laporan-badge--muted {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            border-color: rgba(75, 85, 99, 0.6);
        }

        .laporan-badge--masuk {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            border-color: rgba(21, 128, 61, 0.6);
        }

        .laporan-badge--keluar {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: rgba(217, 119, 6, 0.6);
        }

        .laporan-badge--akhir {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: rgba(29, 78, 216, 0.6);
        }

        .detail-cabang-cell {
            white-space: pre-line;
            font-size: 0.76rem;
            line-height: 1.3;
            word-break: break-word;
            vertical-align: top;
        }

        @media (max-width: 1024px) {
            .laporan-insights {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .laporan-insights {
                grid-template-columns: 1fr;
                gap: 0.8rem;
            }

            .laporan-insight {
                padding: 0.9rem;
            }

            .laporan-filter {
                padding: 0.8rem;
            }

            .laporan-table .table th,
            .laporan-table .table td {
                padding: 0.7rem 0.6rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .card-header {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 0.45rem;
            }

            .card-tools {
                width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.4rem;
            }

            .card-tools .btn {
                width: 100%;
                padding-left: 0.45rem;
                padding-right: 0.45rem;
            }

            .laporan-chip {
                font-size: 0.7rem;
                padding: 0.24rem 0.48rem;
            }

            .laporan-table .table th,
            .laporan-table .table td {
                white-space: nowrap;
            }
        }

        @media print {
            .main-header,
            .main-sidebar,
            .main-footer,
            .content-header,
            .card-tools,
            .btn,
            .nav,
            .navbar,
            .laporan-insights,
            .laporan-meta,
            .laporan-filter {
                display: none !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
                min-height: auto !important;
            }

            .content,
            .container-fluid,
            .row,
            .col-md-12 {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            .card {
                border: 1px solid #d1d5db !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                margin: 0 !important;
            }

            .card-header {
                background: #fff !important;
                color: #111 !important;
                border-bottom: 1px solid #d1d5db !important;
                padding: 10px 12px !important;
            }

            .card-body {
                padding: 0 !important;
            }

            .table-responsive {
                overflow: visible !important;
            }

            .table {
                margin-bottom: 0 !important;
            }

            .table th,
            .table td {
                color: #111 !important;
                background: #fff !important;
                border-color: #d1d5db !important;
                padding: 8px !important;
            }

            .badge {
                border: 1px solid #d1d5db !important;
                background: #fff !important;
                color: #111 !important;
            }

            .print-only-header {
                display: block;
                text-align: center;
                margin-bottom: 12px;
            }

            .print-only-header h2 {
                font-size: 18px;
                margin: 0;
            }

            .print-only-header p {
                font-size: 12px;
                margin: 4px 0 0;
                color: #374151;
            }

            .card-tools {
                display: none;
            }

            body {
                background: white;
            }
        }
    </style>
@endsection
