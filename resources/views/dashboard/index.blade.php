@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @push('styles')
    <style>
        :root {
            --primary-red: #c62833;
            --red-light: #cf202c;
            --red-dark: #8f1b24;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 6px 16px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 20px 48px rgba(0, 0, 0, 0.2);
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        @keyframes floatUp {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
            100% { transform: translateY(0px); }
        }

        /* Dashboard Container */
        .row.g-3 {
            margin-bottom: 2rem;
        }

        /* Modern Premium Stat Card */
        .dashboard-stat {
            border-radius: 1.6rem;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #f5f5f5 100%);
            border: 1px solid rgba(198, 40, 51, 0.12);
            box-shadow: 0 8px 24px rgba(198, 40, 51, 0.08);
            height: 100%;
            overflow: hidden;
            transition: var(--transition);
            position: relative;
            animation: slideInUp 0.7s ease-out forwards;
        }

        .dashboard-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, var(--red-light) 0%, var(--primary-red) 50%, #8f1b24 100%);
            box-shadow: 0 2px 8px rgba(198, 40, 51, 0.2);
        }

        .dashboard-stat::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 100% 0%, rgba(206, 32, 44, 0.05) 0%, transparent 100%);
            pointer-events: none;
        }

        .dashboard-stat:nth-child(1) { animation-delay: 0.1s; }
        .dashboard-stat:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-stat:nth-child(3) { animation-delay: 0.3s; }
        .dashboard-stat:nth-child(4) { animation-delay: 0.4s; }

        .dashboard-stat:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 16px 48px rgba(198, 40, 51, 0.15);
            border-color: rgba(198, 40, 51, 0.25);
        }

        .dashboard-stat__content {
            padding: 1.8rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1.2rem;
            position: relative;
            z-index: 1;
        }

        .dashboard-stat__title {
            margin: 0 0 0.6rem;
            font-size: 0.78rem;
            color: #8f1b24;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 0 1px 2px rgba(198, 40, 51, 0.05);
        }

        .dashboard-stat__value {
            margin: 0;
            font-size: 2.6rem;
            font-weight: 950;
            color: #1a1a1a;
            line-height: 1;
            letter-spacing: -0.8px;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dashboard-stat__icon {
            width: 68px;
            height: 68px;
            border-radius: 1.3rem;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 1.8rem;
            flex-shrink: 0;
            transition: var(--transition);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .dashboard-stat__icon::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .dashboard-stat:hover .dashboard-stat__icon {
            transform: scale(1.15) rotate(-5deg);
            animation: floatUp 0.6s ease-in-out;
        }

        .stat-red { background: linear-gradient(135deg, var(--red-light), var(--primary-red)); }
        .stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
        .stat-green { background: linear-gradient(135deg, #16a34a, #15803d); }
        .stat-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }

        .dashboard-stat__footer {
            border-top: 1px solid rgba(198, 40, 51, 0.1);
            padding: 1rem 1.8rem;
            background: linear-gradient(135deg, rgba(248, 249, 250, 0.8) 0%, rgba(255, 255, 255, 0.5) 100%);
            transition: var(--transition);
            position: relative;
            z-index: 1;
        }

        .dashboard-stat:hover .dashboard-stat__footer {
            background: linear-gradient(135deg, rgba(198, 40, 51, 0.06) 0%, rgba(206, 32, 44, 0.03) 100%);
        }

        .dashboard-stat__footer a {
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--primary-red);
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: var(--transition);
            position: relative;
        }

        .dashboard-stat__footer a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-red), transparent);
            transition: width 0.3s ease;
        }

        .dashboard-stat__footer a:hover {
            color: var(--red-dark);
            gap: 0.9rem;
        }

        .dashboard-stat__footer a:hover::after {
            width: 100%;
        }

        /* Premium Card */
        .card {
            border-radius: 1.6rem;
            border: 1px solid rgba(198, 40, 51, 0.12);
            box-shadow: 0 8px 24px rgba(198, 40, 51, 0.08);
            overflow: hidden;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 50%, #f5f5f5 100%);
            transition: var(--transition);
            animation: slideInUp 0.7s ease-out 0.5s forwards;
            opacity: 0;
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
            z-index: 1;
        }

        .card:hover {
            box-shadow: 0 16px 48px rgba(198, 40, 51, 0.15);
            border-color: rgba(198, 40, 51, 0.25);
            transform: translateY(-4px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--red-light) 0%, var(--primary-red) 50%, #8f1b24 100%) !important;
            color: #fff !important;
            border: none !important;
            padding: 1.8rem !important;
            box-shadow: 0 4px 12px rgba(198, 40, 51, 0.2) !important;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: -40%;
            width: 80%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .card-header .card-title {
            color: #fff !important;
            font-weight: 900 !important;
            font-size: 1.2rem !important;
            margin: 0 !important;
            letter-spacing: 0.8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }

        .card-body {
            padding: 1.8rem;
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 50%, rgba(248, 249, 250, 0.9) 100%);
            position: relative;
            z-index: 2;
        }

        .activity-log-list {
            display: grid;
            gap: 1rem;
        }

        .activity-log-item {
            border: 1px solid rgba(198, 40, 51, 0.1);
            border-radius: 1.1rem;
            padding: 1.1rem;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 248, 248, 0.6) 100%);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .activity-log-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--primary-red), rgba(198, 40, 51, 0.3));
            transform: scaleY(0);
            transform-origin: top;
            transition: transform 0.3s ease;
        }

        .activity-log-item:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 248, 248, 0.9) 100%);
            box-shadow: 0 8px 20px rgba(198, 40, 51, 0.12);
            transform: translateX(6px);
            border-color: rgba(198, 40, 51, 0.2);
        }

        .activity-log-item:hover::before {
            transform: scaleY(1);
        }

        .activity-log-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8rem;
            margin-bottom: 0.4rem;
        }

        .activity-log-user {
            font-weight: 900;
            color: #1a1a1a;
            font-size: 0.95rem;
        }

        .activity-log-time {
            font-size: 0.75rem;
            color: #9ca3af;
            white-space: nowrap;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .activity-log-desc {
            margin: 0;
            color: #4b5563;
            font-weight: 700;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .row.g-3 {
                margin-bottom: 1.8rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-stat__value {
                font-size: 2rem;
            }

            .dashboard-stat__icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .dashboard-stat__content {
                padding: 1.4rem;
            }

            .dashboard-stat__footer {
                padding: 0.9rem 1.4rem;
            }

            .card-header {
                padding: 1.4rem !important;
            }

            .card-body {
                padding: 1.4rem;
            }

            .activity-log-item {
                padding: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .dashboard-stat {
                border-radius: 1.2rem;
            }

            .dashboard-stat__value {
                font-size: 1.6rem;
            }

            .dashboard-stat__icon {
                width: 52px;
                height: 52px;
                font-size: 1.2rem;
            }

            .card {
                border-radius: 1.2rem;
            }
        }
    </style>
    @endpush

    <div class="row g-3">
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat">
                <div class="dashboard-stat__content">
                    <div>
                        <p class="dashboard-stat__title">Total Barang</p>
                        <p class="dashboard-stat__value">{{ $totalBarang }}</p>
                    </div>
                    <div class="dashboard-stat__icon stat-blue"><i class="fas fa-cube"></i></div>
                </div>
                <div class="dashboard-stat__footer">
                    <a href="{{ route('barang.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat">
                <div class="dashboard-stat__content">
                    <div>
                        <p class="dashboard-stat__title">Total Barang Masuk Manual</p>
                        <p class="dashboard-stat__value">{{ $totalMasuk }}</p>
                    </div>
                    <div class="dashboard-stat__icon stat-green"><i class="fas fa-arrow-down"></i></div>
                </div>
                <div class="dashboard-stat__footer">
                    <a href="{{ route('barang-masuk.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat">
                <div class="dashboard-stat__content">
                    <div>
                        <p class="dashboard-stat__title">Total Barang Keluar</p>
                        <p class="dashboard-stat__value">{{ $totalKeluar }}</p>
                    </div>
                    <div class="dashboard-stat__icon stat-amber"><i class="fas fa-arrow-up"></i></div>
                </div>
                <div class="dashboard-stat__footer">
                    <a href="{{ route('barang-keluar.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat">
                <div class="dashboard-stat__content">
                    <div>
                        <p class="dashboard-stat__title">Total Stok</p>
                        <p class="dashboard-stat__value">{{ $totalStok }}</p>
                    </div>
                    <div class="dashboard-stat__icon stat-red"><i class="fas fa-boxes"></i></div>
                </div>
                <div class="dashboard-stat__footer">
                    <a href="{{ route('laporan.index') }}">Lihat detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aktivitas Stok (7 Hari Terakhir)</h3>
                </div>
                <div class="card-body">
                    <canvas id="stockChart" style="height: 380px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->isOwner())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Activity Log Dashboard (5 Input Terakhir Karyawan)</h3>
                        <span class="badge bg-light text-dark">Auto refresh 30 detik</span>
                    </div>
                    <div class="card-body">
                        <div class="activity-log-list">
                            @forelse($recentActivities as $activity)
                                <div class="activity-log-item">
                                    <div class="activity-log-head">
                                        <div>
                                            <span class="activity-log-user">{{ $activity->penginput }}</span>
                                            @if($activity->tipe === 'masuk')
                                                @if(($activity->source ?? null) === 'return')
                                                    <span class="badge bg-info text-dark ms-1">Sisa Kembali</span>
                                                @else
                                                    <span class="badge bg-success ms-1">Masuk Manual</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger ms-1">Keluar</span>
                                            @endif
                                        </div>
                                        <span class="activity-log-time">{{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y H:i') }} WIB</span>
                                    </div>
                                    <p class="activity-log-desc mb-0">
                                        {{ $activity->nama_barang }} - Qty {{ $activity->jumlah }}
                                        @if(($activity->source ?? null) === 'return')
                                            <small class="text-muted">(sisa cabang kembali)</small>
                                        @endif
                                    </p>
                                </div>
                            @empty
                                <div class="text-muted">Belum ada aktivitas input dari karyawan.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        const ctx = document.getElementById('stockChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartData['labels']),
                datasets: [
                    {
                        label: 'Barang Masuk Manual',
                        data: @json($chartData['masukData']),
                        backgroundColor: 'rgba(245, 158, 11, 0.75)',
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    },
                    {
                        label: 'Barang Keluar',
                        data: @json($chartData['keluarData']),
                        backgroundColor: 'rgba(220, 38, 38, 0.75)',
                        borderColor: '#dc2626',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        @if(Auth::user()->isOwner())
            setInterval(function () {
                window.location.reload();
            }, 30000);
        @endif

        // Low Stock Notification Popup
        document.addEventListener('DOMContentLoaded', function () {
            const lowStockItems = @json($lowStockItems);
            
            if (lowStockItems && lowStockItems.length > 0) {
                showLowStockNotification(lowStockItems);
            }
        });

        function showLowStockNotification(items) {
            // Separate items by status
            const habisItems = items.filter(item => item.stok === 0);
            const hampirHabisItems = items.filter(item => item.stok > 0 && item.stok <= 20);

            // Build HTML content for the popup
            let htmlContent = '<div style="text-align: left; max-height: 400px; overflow-y: auto;">';
            
            // Show items that are completely out of stock first
            if (habisItems.length > 0) {
                htmlContent += '<div style="margin-bottom: 1.5rem;">';
                htmlContent += '<h5 style="color: #c62833; margin-bottom: 0.75rem; font-weight: 700;">';
                htmlContent += '<i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>Stok Habis</h5>';
                htmlContent += '<ul style="list-style: none; padding: 0; margin: 0;">';
                
                habisItems.forEach(item => {
                    htmlContent += `<li style="padding: 0.6rem 0; border-bottom: 1px solid #f0d6d8; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; color: #1f2937;">${item.nama_barang}</span>
                        <span style="background-color: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700;">Stok: ${item.stok}</span>
                    </li>`;
                });
                
                htmlContent += '</ul></div>';
            }
            
            // Show items with low stock
            if (hampirHabisItems.length > 0) {
                htmlContent += '<div>';
                htmlContent += '<h5 style="color: #f59e0b; margin-bottom: 0.75rem; font-weight: 700;">';
                htmlContent += '<i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>Stok Hampir Habis</h5>';
                htmlContent += '<ul style="list-style: none; padding: 0; margin: 0;">';
                
                hampirHabisItems.forEach(item => {
                    htmlContent += `<li style="padding: 0.6rem 0; border-bottom: 1px solid #fef3c7; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-weight: 600; color: #1f2937;">${item.nama_barang}</span>
                        <span style="background-color: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 700;">Stok: ${item.stok}</span>
                    </li>`;
                });
                
                htmlContent += '</ul></div>';
            }
            
            htmlContent += '</div>';

            // Show SweetAlert popup
            Swal.fire({
                title: '⚠️ Notifikasi Stok Barang',
                html: htmlContent,
                icon: 'warning',
                confirmButtonText: 'Tindak Lanjuti',
                confirmButtonColor: '#c62833',
                cancelButtonText: 'Tutup',
                showCancelButton: true,
                didOpen: (modal) => {
                    // Highlight the popup title
                    const titleElement = modal.querySelector('.swal2-title');
                    if (titleElement) {
                        titleElement.style.fontSize = '1.3rem';
                        titleElement.style.color = '#c62833';
                    }
                },
                willClose: () => {
                    // Additional action when popup closes
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to barang masuk page when user clicks "Tindak Lanjuti"
                    window.location.href = '{{ route("barang-masuk.index") }}';
                }
            });
        }
    </script>
    @endpush
@endsection
