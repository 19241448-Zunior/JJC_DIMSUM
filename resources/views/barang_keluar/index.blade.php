@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('page-title', 'Barang Keluar')

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

        .barang-keluar-insights {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.2rem;
            margin-bottom: 1.6rem;
        }

        .barang-keluar-insight {
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

        .barang-keluar-insight::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #cf202c 0%, #c62833 100%);
        }

        .barang-keluar-insight:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
        }

        .barang-keluar-insight__label {
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

        .barang-keluar-insight__value {
            color: #2d2d2d;
            font-size: 1.4rem;
            font-weight: 950;
            line-height: 1.2;
        }

        .barang-keluar-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-bottom: 1.5rem;
        }

        .barang-keluar-chip {
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

        .barang-keluar-chip:hover {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.08) 0%, rgba(220, 38, 38, 0.04) 100%);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .barang-keluar-table {
            border-radius: 1.2rem;
            overflow: hidden;
            border: none;
            box-shadow: var(--shadow-lg);
        }

        .barang-keluar-table .table thead th {
            background: linear-gradient(135deg, #cf202c 0%, #c62833 100%);
            color: #fff;
            border: none;
            font-weight: 850;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem 0.8rem;
        }

        .barang-keluar-table .table tbody tr {
            transition: var(--transition);
            border-bottom: 1px solid rgba(198, 40, 51, 0.06);
        }

        .barang-keluar-table .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(220, 38, 38, 0.04) 0%, rgba(220, 38, 38, 0.02) 100%);
            transform: translateX(3px);
        }

        .barang-keluar-no {
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

        .barang-keluar-jumlah {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
            color: #fff;
            border: 1px solid rgba(185, 28, 28, 0.6);
            box-shadow: var(--shadow-md) !important;
            font-weight: 700;
            padding: 0.4rem 0.8rem !important;
        }

        .barang-keluar-tanggal {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-weight: 600;
            color: #2d2d2d;
        }

        @media (max-width: 1024px) {
            .barang-keluar-insights {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .barang-keluar-insights {
                grid-template-columns: 1fr;
                gap: 0.8rem;
            }

            .barang-keluar-insight {
                padding: 0.9rem;
            }

            .barang-keluar-insight__label {
                font-size: 0.75rem;
            }

            .barang-keluar-insight__value {
                font-size: 1.1rem;
            }

            .barang-keluar-table .table th,
            .barang-keluar-table .table td {
                padding: 0.7rem 0.6rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .barang-keluar-meta {
                flex-direction: column;
            }

            .barang-keluar-chip {
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

            .barang-keluar-chip {
                font-size: 0.7rem;
                padding: 0.24rem 0.48rem;
            }

            .barang-keluar-table .table th,
            .barang-keluar-table .table td {
                white-space: nowrap;
            }

            .barang-keluar-tanggal {
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
                $jumlahHalamanIni = $barangKeluar->sum('jumlah');
                $terakhirInput = $barangKeluar->first();
            @endphp

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Barang Keluar</h3>
                    @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                        <div class="card-tools">
                            <a href="{{ route('barang-keluar.create') }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-plus"></i> Tambah
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="barang-keluar-insights">
                        <div class="barang-keluar-insight">
                            <div class="barang-keluar-insight__label"><i class="fas fa-layer-group"></i> Total Entry</div>
                            <div class="barang-keluar-insight__value">{{ $barangKeluar->total() }}</div>
                        </div>
                        <div class="barang-keluar-insight">
                            <div class="barang-keluar-insight__label"><i class="fas fa-box-open"></i> Jumlah Halaman Ini</div>
                            <div class="barang-keluar-insight__value">{{ $jumlahHalamanIni }}</div>
                        </div>
                        <div class="barang-keluar-insight">
                            <div class="barang-keluar-insight__label"><i class="fas fa-history"></i> Input Terakhir</div>
                            <div class="barang-keluar-insight__value">{{ $terakhirInput ? $terakhirInput->created_at->format('d M Y H:i') . ' WIB' : '-' }}</div>
                        </div>
                    </div>

                    <div class="barang-keluar-meta">
                        <span class="barang-keluar-chip"><i class="fas fa-database"></i> Total Entry: {{ $barangKeluar->total() }}</span>
                        <span class="barang-keluar-chip"><i class="fas fa-clock"></i> Sinkron: {{ now()->format('d M Y H:i') }} WIB</span>
                    </div>

                    <div class="table-responsive barang-keluar-table">
                    <table class="table table-bordered table-striped table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%">No</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Penginput</th>
                                <th>Status Void</th>
                                @if(Auth::user()->isOwner() || Auth::user()->isKaryawan())
                                    <th style="width: 20%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if($barangKeluar->count() > 0)
                                @foreach($barangKeluar as $item)
                                    <tr>
                                        <td><span class="barang-keluar-no">{{ ($barangKeluar->currentPage() - 1) * $barangKeluar->perPage() + $loop->iteration }}</span></td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>
                                            <span class="badge barang-keluar-jumlah">{{ $item->jumlah }}</span>
                                        </td>
                                        <td><span class="barang-keluar-tanggal"><i class="far fa-calendar-alt"></i>{{ $item->created_at->format('d M Y H:i') }} WIB</span></td>
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
                                                            <form method="POST" action="{{ route('barang-keluar.approve-void', $item->id) }}">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Setujui void dan hapus data ini?')">
                                                                    <i class="fas fa-check"></i>
                                                                    <span class="action-label">Approve Void</span>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="{{ route('barang-keluar.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                                                <i class="fas fa-edit" aria-hidden="true"></i>
                                                                <span class="action-label">Edit</span>
                                                            </a>
                                                            <form method="POST" action="{{ route('barang-keluar.destroy', $item->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus" aria-label="Hapus barang keluar">
                                                                    <i class="fas fa-trash" aria-hidden="true"></i>
                                                                    <span class="action-label">Hapus</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @elseif(Auth::user()->isKaryawan())
                                                        @if($item->void_status === 'pending')
                                                            <span class="badge bg-warning text-dark">Menunggu approval owner</span>
                                                        @else
                                                            <form method="POST" action="{{ route('barang-keluar.request-void', $item->id) }}" class="void-request-form">
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
                                    <td colspan="{{ Auth::user()->isOwner() || Auth::user()->isKaryawan() ? 7 : 6 }}" class="text-center text-muted">Tidak ada data</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $barangKeluar->links() }}
                    </div>
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
