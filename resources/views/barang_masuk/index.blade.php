@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('page-title', 'Barang Masuk')

@section('content')
    @push('styles')
    <style>
        :root {
            --primary-red: #c62833;
            --red-light: #cf202c;
            --green-primary: #16a34a;
            --green-dark: #15803d;
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes floatUp {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }

        .barang-masuk-insights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.4rem;
            margin-bottom: 2rem;
        }

        .barang-masuk-insight {
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

        .barang-masuk-insight::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, #cf202c 0%, #c62833 50%, #8f1b24 100%);
            box-shadow: 0 2px 8px rgba(198, 40, 51, 0.2);
        }

        .barang-masuk-insight::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(22, 163, 74, 0.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .barang-masuk-insight:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 16px 48px rgba(22, 163, 74, 0.12);
            border-color: rgba(22, 163, 74, 0.25);
        }

        .barang-masuk-insight__label {
            font-size: 0.75rem;
            color: #8f1b24;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-shadow: 0 1px 2px rgba(22, 163, 74, 0.05);
        }

        .barang-masuk-insight__value {
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

        .barang-masuk-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.9rem;
            margin-bottom: 1.8rem;
        }

        .barang-masuk-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border: 1.5px solid rgba(22, 163, 74, 0.15);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(240, 253, 244, 0.8) 100%);
            color: #15803d;
            border-radius: 999px;
            padding: 0.65rem 1.2rem;
            font-size: 0.8rem;
            font-weight: 700;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.08);
            position: relative;
            overflow: hidden;
        }

        .barang-masuk-chip::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 100% 0%, rgba(22, 163, 74, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .barang-masuk-chip:hover {
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.12) 0%, rgba(22, 163, 74, 0.06) 100%);
            border-color: rgba(22, 163, 74, 0.3);
            box-shadow: 0 8px 20px rgba(22, 163, 74, 0.15);
            transform: translateY(-3px) scale(1.05);
        }

        .barang-masuk-table {
            border-radius: 1.6rem;
            overflow: hidden;
            border: 1px solid rgba(22, 163, 74, 0.12);
            box-shadow: 0 8px 24px rgba(22, 163, 74, 0.1);
        }

        .barang-masuk-table .table thead th {
            background: linear-gradient(135deg, #cf202c 0%, #c62833 50%, #8f1b24 100%);
            color: #fff;
            border: none;
            font-weight: 900;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 1.2rem 1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .barang-masuk-table .table tbody tr {
            transition: var(--transition);
            border-bottom: 1px solid rgba(22, 163, 74, 0.08);
        }

        .barang-masuk-table .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(22, 163, 74, 0.08) 0%, rgba(22, 163, 74, 0.04) 100%);
            transform: translateX(4px);
            box-shadow: inset 5px 0 0 0 #16a34a;
        }

        .barang-masuk-table .table td {
            padding: 1.1rem 1rem;
            vertical-align: middle;
            font-weight: 600;
        }

        .barang-masuk-tanggal {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .barang-masuk-no {
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

        .barang-masuk-jumlah {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%) !important;
            color: #fff;
            border: 1px solid rgba(22, 163, 74, 0.3);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.15) !important;
            font-weight: 800 !important;
            padding: 0.5rem 0.9rem !important;
            border-radius: 0.8rem !important;
            font-size: 0.78rem !important;
            letter-spacing: 0.3px;
        }

        @media (max-width: 1024px) {
            .barang-masuk-insights {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .barang-masuk-insights {
                grid-template-columns: 1fr;
                gap: 0.8rem;
            }

            .barang-masuk-insight {
                padding: 0.9rem;
            }

            .barang-masuk-insight__label {
                font-size: 0.75rem;
            }

            .barang-masuk-insight__value {
                font-size: 1.1rem;
            }

            .barang-masuk-table .table th,
            .barang-masuk-table .table td {
                padding: 0.7rem 0.6rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .barang-masuk-meta {
                flex-direction: column;
            }

            .barang-masuk-chip {
                width: 100%;
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
            }

            .card-tools .btn {
                width: 100%;
            }

            .barang-masuk-chip {
                font-size: 0.7rem;
                padding: 0.24rem 0.48rem;
            }

            .barang-masuk-table .table th,
            .barang-masuk-table .table td {
                white-space: nowrap;
            }

            .barang-masuk-tanggal {
                font-size: 0.72rem;
            }

            .actions-inline {
                justify-content: center;
            }

            .actions-inline .btn {
                min-width: 2rem;
                min-height: 2rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.28rem;
            }

            .actions-inline .action-label {
                display: none;
            }
        }
    </style>
    @endpush

    <div class="row">
        <div class="col-md-12">
            @php
                $jumlahHalamanIni = $barangMasuk->sum('jumlah');
                $terakhirInput = $barangMasuk->first();
            @endphp

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Barang Masuk</h3>
                    <div class="card-tools">
                        @if(Auth::user()->isKaryawan())
                            <a href="{{ route('barang-masuk.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        @elseif(Auth::user()->isOwner())
                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#karyawanOnlyModal">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="barang-masuk-insights">
                        <div class="barang-masuk-insight">
                            <div class="barang-masuk-insight__label"><i class="fas fa-layer-group"></i> Total Entry</div>
                            <div class="barang-masuk-insight__value">{{ $barangMasuk->total() }}</div>
                        </div>
                        <div class="barang-masuk-insight">
                            <div class="barang-masuk-insight__label"><i class="fas fa-box-open"></i> Jumlah Halaman Ini</div>
                            <div class="barang-masuk-insight__value">{{ $jumlahHalamanIni }}</div>
                        </div>
                        <div class="barang-masuk-insight">
                            <div class="barang-masuk-insight__label"><i class="fas fa-history"></i> Input Terakhir</div>
                            <div class="barang-masuk-insight__value">{{ $terakhirInput ? $terakhirInput->created_at->format('d M Y H:i') . ' WIB' : '-' }}</div>
                        </div>
                    </div>

                    <div class="barang-masuk-meta">
                        <span class="barang-masuk-chip"><i class="fas fa-database"></i> Total Entry: {{ $barangMasuk->total() }}</span>
                        <span class="barang-masuk-chip"><i class="fas fa-clock"></i> Sinkron: {{ now()->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div class="table-responsive barang-masuk-table">
                    <table class="table table-bordered table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Barang</th>
                                <th>Sumber</th>
                                <th>Jumlah</th>
                                <th>Tanggal Masuk</th>
                                <th>Penginput</th>
                                <th>Status Void</th>
                                @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                                    <th style="width: 20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($barangMasuk->count() > 0)
                                @foreach($barangMasuk as $item)
                                    <tr>
                                        <td>
                                            <span class="barang-masuk-no">{{ ($barangMasuk->currentPage() - 1) * $barangMasuk->perPage() + $loop->iteration }}</span>
                                        </td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>
                                            @if(($item->source ?? 'manual') === 'return')
                                                <span class="badge bg-info text-dark">Sisa Cabang</span>
                                            @else
                                                <span class="badge bg-success">Manual</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge barang-masuk-jumlah">{{ $item->jumlah }}</span>
                                        </td>
                                        <td>
                                            <span class="barang-masuk-tanggal">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $item->created_at->format('d M Y H:i') }} WIB
                                            </span>
                                        </td>
                                        <td>{{ $item->user?->name ?? '-' }}</td>
                                        <td>
                                            @if($item->void_status === 'pending')
                                                <span class="badge bg-warning text-dark">Pending Void</span>
                                            @elseif($item->void_status === 'approved')
                                                <span class="badge bg-danger">Voided</span>
                                            @else
                                                <span class="badge bg-success">Normal</span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                                            <td>
                                                <div class="actions-inline">
                                                    @if(Auth::user()->isOwner())
                                                        @if($item->void_status === 'pending')
                                                            <form method="POST" action="{{ route('barang-masuk.approve-void', $item->id) }}">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Setujui void dan hapus data ini?')">
                                                                    <i class="fas fa-check"></i>
                                                                    <span class="action-label">Approve Void</span>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="{{ route('barang-masuk.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                                                <span class="action-label">Edit</span>
                                                            </a>
                                                            <form method="POST" action="{{ route('barang-masuk.destroy', $item->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus" aria-label="Hapus barang masuk">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                    <span class="action-label">Hapus</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @elseif(Auth::user()->isKaryawan())
                                                        @if($item->void_status === 'pending')
                                                            <span class="badge bg-warning text-dark">Menunggu approval owner</span>
                                                        @else
                                                            <form method="POST" action="{{ route('barang-masuk.request-void', $item->id) }}" class="void-request-form">
                                                                @csrf
                                                                <input type="hidden" name="void_reason" value="">
                                                                <button type="button" class="btn btn-outline-danger btn-sm js-btn-void">
                                                                    <i class="fas fa-ban"></i>
                                                                    <span class="action-label">Request Void</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                                @if($item->void_status === 'pending' && $item->void_reason)
                                                    <small class="text-muted d-block mt-1">Alasan: {{ $item->void_reason }}</small>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ Auth::user()->isOwner() || Auth::user()->isKaryawan() ? 8 : 7 }}" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $barangMasuk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="karyawanOnlyModal" tabindex="-1" aria-labelledby="karyawanOnlyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="karyawanOnlyModalLabel">Akses Ditolak</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Akses ini hanya untuk karyawan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.querySelectorAll('.void-request-form .js-btn-void').forEach(function (button) {
            button.addEventListener('click', function () {
                const form = button.closest('form');
                const reason = window.prompt('Masukkan alasan request void (minimal 10 karakter):');

                if (!reason) {
                    return;
                }

                if (reason.trim().length < 10) {
                    alert('Alasan minimal 10 karakter.');
                    return;
                }

                form.querySelector('input[name="void_reason"]').value = reason.trim();
                form.submit();
            });
        });
    </script>
    @endpush
@endsection
