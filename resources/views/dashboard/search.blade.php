@extends('layouts.app')

@section('title', 'Hasil Pencarian Barang')
@section('page-title', 'Hasil Pencarian Barang')

@section('content')
    @push('styles')
    <style>
        .search-hero {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, #c62833 0%, #8f1b24 100%);
            color: #fff;
            box-shadow: 0 14px 36px rgba(198, 40, 51, 0.2);
        }

        .search-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 100% 0%, rgba(255, 237, 78, 0.18) 0%, transparent 32%);
            pointer-events: none;
        }

        .search-hero__inner {
            position: relative;
            z-index: 1;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .search-hero__title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 900;
        }

        .search-hero__meta {
            margin-top: 0.45rem;
            opacity: 0.95;
        }

        .search-chip-row {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            margin-top: 1rem;
        }

        .search-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-weight: 700;
        }

        .result-card {
            border-radius: 1.1rem;
            border: 1px solid rgba(198, 40, 51, 0.12);
            box-shadow: 0 10px 24px rgba(198, 40, 51, 0.08);
            overflow: hidden;
            background: #fff;
        }

        .result-card__head {
            padding: 1rem 1.15rem;
            background: linear-gradient(90deg, rgba(198, 40, 51, 0.08), rgba(198, 40, 51, 0.02));
            border-bottom: 1px solid rgba(198, 40, 51, 0.08);
        }

        .result-card__title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 800;
            color: #1f2937;
        }

        .result-card__body {
            padding: 1.1rem;
        }

        .result-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.85rem;
            margin-top: 1rem;
        }

        .result-kpi {
            border-radius: 1rem;
            padding: 0.95rem;
            background: linear-gradient(180deg, #fff 0%, #fcfcfd 100%);
            border: 1px solid #eef0f5;
        }

        .result-kpi__label {
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8f1b24;
            font-weight: 800;
        }

        .result-kpi__value {
            margin-top: 0.35rem;
            font-size: 1.35rem;
            font-weight: 900;
            color: #111827;
        }

        .result-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 1rem;
        }

        .result-actions .btn {
            border-radius: 0.75rem;
        }

        @media (max-width: 992px) {
            .result-kpi-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 576px) {
            .search-hero__inner {
                padding: 1.2rem;
            }

            .search-hero__title {
                font-size: 1.25rem;
            }

            .result-kpi-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @endpush

    <div class="search-hero mb-4">
        <div class="search-hero__inner">
            <div>
                <h2 class="search-hero__title">Hasil Pencarian: "{{ $query }}"</h2>
                <div class="search-hero__meta">
                    {{ $matchedCount }} barang ditemukan. Klik nama barang untuk membuka detail di tab baru.
                </div>
                <div class="search-chip-row">
                    <span class="search-chip"><i class="fas fa-boxes"></i> Total Barang: {{ $totalBarang }}</span>
                    <span class="search-chip"><i class="fas fa-arrow-down"></i> Masuk: {{ $totalMasuk }}</span>
                    <span class="search-chip"><i class="fas fa-arrow-up"></i> Keluar: {{ $totalKeluar }}</span>
                    <span class="search-chip"><i class="fas fa-clipboard-check"></i> Stok Sistem: {{ $totalStok }}</span>
                </div>
            </div>
            <div class="text-end">
                <a href="{{ route('dashboard') }}" class="btn btn-light fw-bold">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    @if($results->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="fas fa-search fa-3x text-muted"></i>
                </div>
                <h4 class="mb-2">Barang tidak ditemukan</h4>
                <p class="text-muted mb-0">Coba cari dengan nama barang atau kode barang yang lain.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($results as $res)
                @php $barang = $res['barang']; @endphp
                <div class="col-12">
                    <div class="result-card">
                        <div class="result-card__head d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <h3 class="result-card__title mb-0">
                                    <a href="{{ route('barang.show', $barang->id) }}" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                                        {{ $barang->nama_barang }}
                                    </a>
                                    <small class="text-muted">({{ $barang->kode_barang }})</small>
                                </h3>
                                <div class="text-muted mt-1">Satuan: {{ $barang->satuan ?? '-' }} | Stok minimal: {{ $barang->stok_min ?? 5 }} | Status: {{ $barang->status ?? 'unknown' }}</div>
                            </div>
                            <div class="result-actions">
                                <a href="{{ route('barang.show', $barang->id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm">
                                    <i class="fas fa-up-right-from-square me-1"></i> Buka Detail
                                </a>
                            </div>
                        </div>

                        <div class="result-card__body">
                            <div class="result-kpi-grid">
                                <div class="result-kpi">
                                    <div class="result-kpi__label">Total Masuk</div>
                                    <div class="result-kpi__value">{{ $res['total_masuk'] }}</div>
                                </div>
                                <div class="result-kpi">
                                    <div class="result-kpi__label">Total Keluar</div>
                                    <div class="result-kpi__value">{{ $res['total_keluar'] }}</div>
                                </div>
                                <div class="result-kpi">
                                    <div class="result-kpi__label">Stok Opname (Fisik)</div>
                                    <div class="result-kpi__value">{{ $res['stok_opname'] }}</div>
                                </div>
                                <div class="result-kpi">
                                    <div class="result-kpi__label">Stok Sistem</div>
                                    <div class="result-kpi__value">{{ $barang->stok }}</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <h5 class="mb-3 fw-bold">Per Cabang</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Cabang</th>
                                                <th>Bawa</th>
                                                <th>Sisa</th>
                                                <th>Terpakai</th>
                                                <th>Masuk</th>
                                                <th>Keluar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($res['per_cabang'] as $pc)
                                                <tr>
                                                    <td>{{ $pc['nama_cabang'] }}</td>
                                                    <td>{{ $pc['jumlah_bawa'] }}</td>
                                                    <td>{{ $pc['jumlah_sisa'] }}</td>
                                                    <td>{{ $pc['jumlah_terpakai'] }}</td>
                                                    <td>{{ $pc['masuk'] }}</td>
                                                    <td>{{ $pc['keluar'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">Belum ada distribusi atau pergerakan cabang untuk barang ini.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
