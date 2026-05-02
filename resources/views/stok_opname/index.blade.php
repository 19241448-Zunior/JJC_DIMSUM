@extends('layouts.app')

@section('title', 'Operasional Cabang Harian')
@section('page-title', 'Operasional Cabang Harian')

@section('content')
    @push('styles')
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

        .card {
            border: 1px solid rgba(198, 40, 51, 0.08);
            box-shadow: var(--shadow-lg);
            border-radius: 1.2rem;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #cf202c 0%, #c62833 100%);
            color: #fff;
            border: none;
            padding: 1.2rem;
        }

        .card-title {
            font-weight: 850;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #2d2d2d;
            margin-bottom: 0.6rem;
        }

        .form-control,
        .form-select {
            border: 1px solid rgba(198, 40, 51, 0.12);
            border-radius: 0.7rem;
            padding: 0.7rem 0.9rem;
            transition: var(--transition);
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-red);
            box-shadow: 0 0 0 3px rgba(198, 40, 51, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            border-radius: 0.7rem;
            font-weight: 600;
            padding: 0.7rem 1.2rem;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            border: none;
            border-radius: 0.7rem;
            font-weight: 600;
            padding: 0.7rem 1.2rem;
            transition: var(--transition);
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .cabang-card {
            border: 1px solid rgba(198, 40, 51, 0.12);
            border-radius: 1rem;
            padding: 1.2rem;
            transition: var(--transition);
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            box-shadow: var(--shadow-md);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .cabang-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #cf202c 0%, #c62833 100%);
        }

        .cabang-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(198, 40, 51, 0.25);
        }

        .cabang-card strong {
            color: #2d2d2d;
            font-weight: 850;
            display: block;
            margin-bottom: 0.4rem;
        }

        .cabang-card .text-muted {
            color: #6b7280;
            font-size: 0.85rem;
        }

        .cabang-button-group {
            display: flex;
            gap: 0.6rem;
        }

        .cabang-button-group .btn {
            flex: 1;
            padding: 0.5rem 0.8rem;
            font-size: 0.85rem;
        }

        .history-table {
            border-radius: 1.2rem;
            overflow: hidden;
            border: none;
            box-shadow: var(--shadow-lg);
        }

        .history-table .table {
            margin-bottom: 0;
        }

        .history-table .table thead th {
            background: linear-gradient(135deg, #cf202c 0%, #c62833 100%);
            color: #fff;
            border: none;
            font-weight: 850;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.8rem;
        }

        .history-table .table tbody tr {
            transition: var(--transition);
            border-bottom: 1px solid rgba(198, 40, 51, 0.06);
        }

        .history-table .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(206, 32, 44, 0.04) 0%, rgba(206, 32, 44, 0.02) 100%);
        }

        .history-table .table td {
            padding: 0.95rem 0.8rem;
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .card-header {
                padding: 0.9rem;
            }

            .card-body {
                padding: 1rem;
            }

            .form-label {
                font-size: 0.85rem;
            }

            .form-control,
            .form-select {
                font-size: 0.9rem;
            }

            .cabang-card {
                padding: 0.9rem;
            }

            .history-table .table th,
            .history-table .table td {
                padding: 0.7rem 0.6rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .btn-primary,
            .btn-secondary {
                width: 100%;
            }

            .cabang-button-group {
                flex-direction: column;
            }

            .cabang-button-group .btn {
                width: 100%;
            }
        }
    </style>
    @endpush
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Tanggal & Cabang Operasional</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('stok-opname.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="tanggal_filter" class="form-label">Tanggal Operasional</label>
                            <input type="date" id="tanggal_filter" name="tanggal" class="form-control" value="{{ $selectedTanggal }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="cabang_filter" class="form-label">Cabang</label>
                            <select id="cabang_filter" name="cabang_id" class="form-select" required>
                                <option value="">-- Pilih Cabang --</option>
                                @foreach($cabangList as $cabang)
                                    <option value="{{ $cabang->id }}" {{ (string) $selectedCabang === (string) $cabang->id ? 'selected' : '' }}>{{ $cabang->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Tampilkan</button>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </form>
                    
                    <!-- Daftar cabang: satu section per cabang -->
                    <div class="mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Pilih Cabang (klik untuk input operasional)</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($cabangList as $cabang)
                                        <div class="col-12 col-md-6">
                                            <a href="{{ route('stok-opname.cabang', $cabang->id) }}?tanggal={{ $selectedTanggal }}#input-pagi" class="text-decoration-none">
                                                <div class="cabang-card">
                                                    <div>
                                                        <strong>{{ $cabang->nama_cabang }}</strong>
                                                        <div class="text-muted">{{ $cabang->kode_cabang ?? '' }} • ID: {{ $cabang->id }}</div>
                                                    </div>
                                                    <div class="cabang-button-group mt-2">
                                                        <a href="{{ route('stok-opname.cabang', $cabang->id) }}?tanggal={{ $selectedTanggal }}#input-pagi" class="btn btn-primary btn-sm">☀️ Pagi</a>
                                                        <a href="{{ route('stok-opname.cabang', $cabang->id) }}?tanggal={{ $selectedTanggal }}#input-malam" class="btn btn-secondary btn-sm">🌙 Malam</a>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Input Hari Ini</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive history-table">
                        <table class="table table-bordered table-sm mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Cabang</th>
                                    <th class="text-center">Total Dibawa</th>
                                    <th class="text-center">Total Sisa</th>
                                    <th class="text-center">Total Terpakai</th>
                                    <th class="text-center">Waktu Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($todayRecords as $record)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $record->cabang?->nama_cabang ?? '-' }}</td>
                                        <td class="text-center">{{ $record->items->sum('jumlah_bawa') }}</td>
                                        <td class="text-center">{{ $record->items->sum('jumlah_sisa') }}</td>
                                        <td class="text-center">{{ $record->items->sum('jumlah_terpakai') }}</td>
                                        <td class="text-center">{{ $record->created_at->format('d M Y H:i') }} WIB</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada input operasional cabang hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('table tbody tr').forEach(function (row) {
            const bawaInput = row.querySelector('.js-malam-bawa');
            const sisaInput = row.querySelector('.js-malam-sisa');
            const terpakaiInput = row.querySelector('.js-malam-terpakai');

            if (!bawaInput || !sisaInput || !terpakaiInput) {
                return;
            }

            const recalc = function () {
                const bawa = parseInt(bawaInput.value || '0', 10);
                const sisa = parseInt(sisaInput.value || '0', 10);

                if (sisa > bawa) {
                    sisaInput.value = String(bawa);
                }

                terpakaiInput.value = Math.max(bawa - parseInt(sisaInput.value || '0', 10), 0);
            };

            sisaInput.addEventListener('input', recalc);
            recalc();
        });

        // Smooth-scroll to section when URL contains a hash (e.g. #input-pagi)
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.hash) {
                try {
                    var el = document.querySelector(window.location.hash);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        var focusable = el.querySelector('input, select, textarea, button');
                        if (focusable) focusable.focus();
                    }
                } catch (e) {
                    // ignore invalid selector
                }
            }
        });
    </script>
    @endpush
@endsection
