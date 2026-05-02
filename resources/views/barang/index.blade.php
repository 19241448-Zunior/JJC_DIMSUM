@extends('layouts.app')

@section('title', 'Data Barang')
@section('page-title', 'Data Barang')

@section('content')
    @push('styles')
    <style>
        :root {
            --primary-red: #c62833;
            --red-light: #cf202c;
            --red-dark: #8f1b24;
            --blue-primary: #2563eb;
            --blue-dark: #1d4ed8;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 6px 16px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 20px 48px rgba(0, 0, 0, 0.2);
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .barang-insights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.4rem;
            margin-bottom: 2rem;
        }

        .barang-insight {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #f5f5f5 100%);
            border: 1px solid rgba(198, 40, 51, 0.12);
            border-radius: 1.6rem;
            padding: 1.6rem;
            box-shadow: 0 8px 24px rgba(198, 40, 51, 0.08);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
            animation: slideInDown 0.7s ease forwards;
        }

        .barang-insight::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #cf202c 0%, #c62833 50%, #8f1b24 100%);
            box-shadow: 0 2px 8px rgba(198, 40, 51, 0.2);
        }

        .barang-insight::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(206, 32, 44, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .barang-insight:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 16px 48px rgba(198, 40, 51, 0.15);
            border-color: rgba(198, 40, 51, 0.25);
        }

        .barang-insight__label {
            font-size: 0.75rem;
            color: #8f1b24;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-shadow: 0 1px 2px rgba(198, 40, 51, 0.05);
        }

        .barang-insight__label i {
            font-size: 1.1rem;
            color: var(--primary-red);
        }

        .barang-insight__value {
            color: #1a1a1a;
            font-size: 2.2rem;
            font-weight: 950;
            line-height: 1.1;
            letter-spacing: -0.8px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .barang-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            margin-bottom: 1.8rem;
        }

        .barang-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border: 1.5px solid rgba(198, 40, 51, 0.15);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 248, 248, 0.8) 100%);
            color: #8f1b24;
            border-radius: 999px;
            padding: 0.65rem 1.2rem;
            font-size: 0.8rem;
            font-weight: 700;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(198, 40, 51, 0.08);
            position: relative;
            overflow: hidden;
        }

        .barang-chip::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 100% 0%, rgba(206, 32, 44, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .barang-chip:hover {
            background: linear-gradient(135deg, rgba(206, 32, 44, 0.12) 0%, rgba(206, 32, 44, 0.06) 100%);
            border-color: rgba(198, 40, 51, 0.3);
            box-shadow: 0 8px 20px rgba(198, 40, 51, 0.15);
            transform: translateY(-3px) scale(1.05);
        }

        .barang-chip i {
            font-size: 0.9rem;
        }

        .barang-table {
            border-radius: 1.6rem;
            overflow: hidden;
            border: 1px solid rgba(198, 40, 51, 0.12);
            box-shadow: 0 8px 24px rgba(198, 40, 51, 0.1);
            animation: fadeIn 0.7s ease forwards;
        }

        .barang-table .table {
            margin-bottom: 0;
        }

        .barang-table .table thead th {
            background: linear-gradient(135deg, #cf202c 0%, #c62833 50%, #8f1b24 100%);
            color: #fff;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
            font-weight: 900;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 1.2rem 1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .barang-table .table tbody tr {
            transition: var(--transition);
            border-bottom: 1px solid rgba(198, 40, 51, 0.08);
        }

        .barang-table .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(206, 32, 44, 0.08) 0%, rgba(206, 32, 44, 0.04) 100%);
            transform: translateX(4px);
            box-shadow: inset 5px 0 0 0 var(--primary-red);
        }

        .barang-table .table td {
            padding: 1.1rem 1rem;
            vertical-align: middle;
            font-weight: 600;
        }

        .barang-no {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.2rem;
            height: 2.2rem;
            border-radius: 0.9rem;
            background: linear-gradient(135deg, #cf202c 0%, #c62833 100%);
            color: #fff;
            font-weight: 900;
            font-size: 0.75rem;
            box-shadow: 0 4px 12px rgba(198, 40, 51, 0.2);
            letter-spacing: 0.5px;
        }

        .barang-stok {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            color: #fff;
            border: 1px solid rgba(29, 78, 216, 0.3);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.15) !important;
            font-weight: 800 !important;
            padding: 0.5rem 0.9rem !important;
            border-radius: 0.8rem !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.3px;
        }

        .actions-inline {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            justify-content: center;
        }

        .actions-inline .btn {
            padding: 0.45rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.7rem;
            transition: var(--transition);
            font-weight: 700;
            border: none;
        }

        .actions-inline .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #fff;
        }

        .actions-inline .btn-warning:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.3);
        }

        .actions-inline .btn-danger {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: #fff;
        }

        .actions-inline .btn-danger:hover {
            background: linear-gradient(135deg, #b91c1c, #991b1b);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(185, 28, 28, 0.3);
        }

        .card {
            border: 1px solid rgba(198, 40, 51, 0.12);
            box-shadow: 0 8px 24px rgba(198, 40, 51, 0.1);
            border-radius: 1.6rem;
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #f5f5f5 100%);
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 100% 0%, rgba(206, 32, 44, 0.03) 0%, transparent 100%);
            pointer-events: none;
            z-index: 0;
        }

        .card-header {
            background: linear-gradient(135deg, #cf202c 0%, #c62833 50%, #8f1b24 100%);
            color: #fff;
            border: none;
            padding: 1.6rem;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: -30%;
            width: 60%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .card-title {
            font-weight: 900;
            font-size: 1.1rem;
            letter-spacing: 0.8px;
            margin: 0;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 2;
        }

        .card-tools {
            display: flex;
            gap: 0.9rem;
        }

        .card-tools .btn {
            border-radius: 0.8rem;
            font-weight: 700;
            padding: 0.6rem 1.2rem;
            transition: var(--transition);
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }

        .card-tools .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            color: #fff;
        }

        .card-tools .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
        }

        .card-body {
            padding: 1.6rem;
            position: relative;
            z-index: 1;
        }

        @media (max-width: 1024px) {
            .barang-insights {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.2rem;
            }
        }

        @media (max-width: 768px) {
            .barang-insights {
                grid-template-columns: 1fr;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .barang-insight {
                padding: 1.2rem;
                border-radius: 1.3rem;
            }

            .barang-insight__label {
                font-size: 0.7rem;
            }

            .barang-insight__value {
                font-size: 1.8rem;
            }

            .barang-table .table {
                font-size: 0.85rem;
            }

            .barang-table .table td {
                padding: 0.9rem 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .barang-insight {
                border-radius: 1rem;
            }

            .barang-chip {
                padding: 0.5rem 0.9rem;
                font-size: 0.75rem;
            }
        }
    </style>
    @endpush

    <div class="row">
        <div class="col-md-12">
            @php
                $totalStokHalaman = $barang->sum('stok');
                $barangStokTertinggi = $barang->sortByDesc('stok')->first();
            @endphp

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Barang</h3>
                    @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                        <div class="card-tools">
                            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Tambah Barang
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="barang-insights">
                        <div class="barang-insight">
                            <div class="barang-insight__label"><i class="fas fa-layer-group"></i> Total Barang</div>
                            <div class="barang-insight__value">{{ $barang->total() }}</div>
                        </div>
                        <div class="barang-insight">
                            <div class="barang-insight__label"><i class="fas fa-boxes"></i> Total Stok Halaman Ini</div>
                            <div class="barang-insight__value">{{ $totalStokHalaman }}</div>
                        </div>
                        <div class="barang-insight">
                            <div class="barang-insight__label"><i class="fas fa-trophy"></i> Stok Tertinggi</div>
                            <div class="barang-insight__value">{{ $barangStokTertinggi ? $barangStokTertinggi->nama_barang . ' (' . $barangStokTertinggi->stok . ')' : '-' }}</div>
                        </div>
                    </div>

                    <div class="barang-meta">
                        <span class="barang-chip"><i class="fas fa-database"></i> Total Barang: {{ $barang->total() }}</span>
                        <span class="barang-chip"><i class="fas fa-clock"></i> Sinkron: {{ now()->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div class="table-responsive barang-table">
                    <table class="table table-bordered table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Nama Barang</th>
                                <th style="width: 15%">Stok</th>
                                @if(Auth::user()->isOwner())
                                    <th style="width: 20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($barang->count() > 0)
                                @foreach($barang as $item)
                                    <tr>
                                        <td><span class="barang-no">{{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}</span></td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>
                                            <span class="badge barang-stok">{{ $item->stok }}</span>
                                        </td>
                                        @if(Auth::user()->isOwner())
                                            <td>
                                                <div class="actions-inline">
                                                    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit" aria-hidden="true"></i>
                                                        <span class="action-label">Edit</span>
                                                    </a>
                                                    <form method="POST" action="{{ route('barang.destroy', $item->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus" aria-label="Hapus data barang">
                                                            <i class="fas fa-trash" aria-hidden="true"></i>
                                                            <span class="action-label">Hapus</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ Auth::user()->isOwner() ? 4 : 3 }}" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $barang->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
