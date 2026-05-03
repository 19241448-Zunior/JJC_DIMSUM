<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Jajanan Cikampek</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

    <style>
        :root {
            --primary-yellow: #FFED4E;
            --primary-red: #E31E24;
            --accent-red: #DC2626;
            --text-dark: #2D2D2D;
            --surface-0: #f6f7fb;
            --surface-1: #ffffff;
            --surface-2: #f9fafb;
            --border-soft: #eceff4;
            --shadow-soft: 0 10px 30px rgba(17, 24, 39, 0.08);
            --shadow-hover: 0 14px 36px rgba(17, 24, 39, 0.12);
        }

        html {
            width: 100%;
            overflow-x: hidden;
            -webkit-text-size-adjust: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
            min-width: 320px;
            min-height: 100vh;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 8% 0%, rgba(227, 30, 36, 0.06) 0%, rgba(227, 30, 36, 0) 34%),
                radial-gradient(circle at 95% 12%, rgba(255, 237, 78, 0.14) 0%, rgba(255, 237, 78, 0) 28%),
                var(--surface-0);
        }

        input,
        select,
        textarea,
        button {
            font: inherit;
        }

        .content {
            padding-bottom: 1.25rem;
        }

        .content .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        /* Navbar Styling */
        .main-header.navbar {
            background: linear-gradient(90deg, var(--accent-red) 0%, #B91720 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
        }

        .main-header.navbar .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
            color: white !important;
        }

        .menu-toggle-btn {
            color: #fff !important;
            border-radius: 0.5rem;
            padding: 0.45rem 0.6rem !important;
            transition: background-color 0.2s ease;
        }

        .menu-toggle-btn:hover,
        .menu-toggle-btn:focus {
            background-color: rgba(255, 255, 255, 0.14);
        }

        .navbar-brand-combined {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.2px;
        }

        .navbar-brand-combined:hover {
            color: #fff;
        }

        .navbar-brand-logo {
            width: 34px;
            aspect-ratio: 1 / 1;
            border-radius: 999px;
            border: 1px solid rgba(255, 237, 78, 0.55);
            background: rgba(255, 255, 255, 0.16);
            padding: 3px;
            object-fit: contain;
        }

        .navbar-brand-label {
            white-space: nowrap;
            line-height: 1;
        }

        .navbar-search-form {
            flex: 1 1 420px;
            max-width: 560px;
            margin: 0 1rem;
        }

        .navbar-search-form .input-group {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 999px;
            overflow: hidden;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        .navbar-search-form .form-control {
            background: transparent;
            color: #fff;
            border: none;
            box-shadow: none;
            padding-left: 1rem;
        }

        .navbar-search-form .form-control::placeholder {
            color: rgba(255, 255, 255, 0.78);
        }

        .navbar-search-form .form-control:focus {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .navbar-search-form .btn {
            border: none;
            background: rgba(255, 237, 78, 0.95);
            color: #2d2d2d;
            font-weight: 800;
            padding-inline: 1rem;
        }

        .navbar-search-form .btn:hover {
            background: #ffed4e;
            color: #1f2937;
        }

        .navbar-text {
            color: white;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.55rem;
            flex-wrap: wrap;
            min-width: 0;
        }

        .navbar-user-name {
            display: inline-flex;
            align-items: center;
            max-width: 220px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .navbar-role-badge {
            white-space: nowrap;
        }

        .navbar-logout-form {
            margin: 0;
        }

        .navbar-text .badge {
            background-color: var(--primary-yellow) !important;
            color: var(--text-dark) !important;
            font-weight: 600;
        }

        /* Sidebar Styling */
        .main-sidebar {
            background: linear-gradient(180deg, #2C2C2C 0%, #1a1a1a 100%);
        }

        @media (min-width: 992px) {
            .main-sidebar,
            .main-header,
            .content-wrapper,
            .main-footer {
                transition: margin-left 0.22s ease, transform 0.22s ease;
            }

            body.sidebar-full-hide .main-sidebar {
                margin-left: -250px;
            }

            body.sidebar-full-hide .main-header,
            body.sidebar-full-hide .content-wrapper,
            body.sidebar-full-hide .main-footer {
                margin-left: 0 !important;
            }

            body.sidebar-full-hide .main-footer {
                left: 0;
            }
        }

        @media (max-width: 991.98px) {
            .main-footer {
                left: 0;
                margin-left: 0 !important;
            }
        }

        @media (max-width: 767px) {
            .navbar-brand-label {
                display: none;
            }

            .navbar-search-form {
                flex-basis: 100%;
                max-width: none;
                margin: 0.5rem 0 0;
                order: 3;
            }

            .main-header.navbar {
                flex-wrap: wrap;
                padding-bottom: 0.65rem;
            }
        }

        .brand-link {
            background: linear-gradient(135deg, var(--accent-red) 0%, #B91720 100%);
            border-bottom: 3px solid var(--primary-yellow);
            padding: 1rem !important;
            display: flex;
            align-items: center;
        }

        .brand-link .brand-text {
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .brand-logo-image {
            width: 38px;
            aspect-ratio: 1 / 1;
            border-radius: 999px;
            border: 1px solid rgba(255, 237, 78, 0.55);
            background: rgba(255, 255, 255, 0.16);
            padding: 4px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-mini.sidebar-collapse .brand-link {
            justify-content: center;
        }

        .nav-sidebar .nav-link {
            color: #bbb;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-sidebar .nav-link:hover {
            background-color: rgba(227, 30, 36, 0.1);
            border-left-color: var(--accent-red);
            color: white;
        }

        .nav-sidebar .nav-link.active {
            background-color: var(--accent-red);
            border-left-color: var(--primary-yellow);
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .nav-sidebar .nav-link i {
            margin-right: 0.5rem;
        }

        /* Content Area */
        .content-wrapper {
            background-color: #f8f9fa;
            margin-top: 57px;
            padding-bottom: 72px;
            min-height: calc(100vh - 57px);
            overflow-x: clip;
        }

        .content-header {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96) 0%, rgba(255, 255, 255, 0.88) 100%);
            border-bottom: 1px solid var(--border-soft);
            padding: 1rem 0 0.85rem;
            backdrop-filter: blur(4px);
        }

        .content-header h1 {
            color: var(--accent-red);
            font-weight: bold;
            font-size: 2rem;
            letter-spacing: -0.02em;
        }

        .card-title {
            margin: 0;
        }

        .table-responsive {
            border-radius: 0.5rem;
            border: 1px solid #eef0f4;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
            -webkit-overflow-scrolling: touch;
        }

        .table td,
        .table th {
            vertical-align: middle;
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 0.4rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
            border: 1px solid #d6dbe3;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: rgba(220, 38, 38, 0.45);
            box-shadow: 0 0 0 0.24rem rgba(220, 38, 38, 0.12);
        }

        .actions-inline {
            display: flex;
            flex-wrap: wrap;
            gap: 0.35rem;
            align-items: center;
        }

        /* Card Styling */
        .card {
            background: var(--surface-1);
            box-shadow: var(--shadow-soft);
            border: 1px solid #edf0f5;
            border-radius: 0.95rem;
            border-top: 4px solid var(--accent-red);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-hover);
        }

        .card-header {
            background: linear-gradient(90deg, var(--accent-red) 0%, #B91720 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 0.95rem 1.2rem;
        }

        .card-body {
            padding: 1.2rem;
        }

        /* Alert Styling */
        .alert {
            border-radius: 0.5rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Button Styling */
        .btn-primary {
            background-color: var(--accent-red);
            border-color: var(--accent-red);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn {
            border-radius: 0.58rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        .btn-primary:hover {
            background-color: #B91720;
            border-color: #B91720;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 30, 36, 0.3);
        }

        .btn:focus-visible {
            box-shadow: 0 0 0 0.24rem rgba(220, 38, 38, 0.2);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--accent-red);
            border-color: var(--accent-red);
            font-weight: 600;
        }

        .btn-danger:hover {
            background-color: #B91720;
            border-color: #B91720;
        }

        /* Table Styling */
        .table {
            background-color: white;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead {
            background-color: var(--accent-red);
            color: white;
        }

        .table thead th {
            font-weight: 600;
            border: none;
            padding: 1rem;
            white-space: nowrap;
            letter-spacing: 0.01em;
        }

        .table tbody tr:nth-child(even) {
            background-color: #fcfcfd;
        }

        .table tbody tr:hover {
            background-color: #fff5f5;
        }

        .table tbody td {
            border-color: #edf0f4;
        }

        /* Badge Styling */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            border-radius: 999px;
        }

        /* Pagination Styling */
        .pagination {
            gap: 0.35rem;
        }

        .page-item .page-link {
            border-radius: 0.45rem;
            border: 1px solid #e5e7eb;
            color: var(--accent-red);
            font-weight: 600;
            padding: 0.45rem 0.75rem;
        }

        .page-item .page-link:hover {
            background-color: #fff1f2;
            border-color: #fecdd3;
            color: #b91c1c;
        }

        .page-item.active .page-link {
            background-color: var(--accent-red);
            border-color: var(--accent-red);
            color: #fff;
            box-shadow: 0 2px 8px rgba(227, 30, 36, 0.25);
        }

        .page-item.disabled .page-link {
            color: #9ca3af;
            background-color: #f9fafb;
            border-color: #e5e7eb;
        }

        /* Footer */
        .main-footer {
            background-color: #2C2C2C;
            color: #bbb;
            border-top: 3px solid var(--accent-red);
            padding: 0.85rem 1.5rem;
            position: static;
            font-size: 0.8rem;
            line-height: 1.45;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
        }

        .main-footer .footer-line {
            display: block;
        }

        .main-footer .footer-left {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        .main-footer strong {
            font-weight: 700;
            letter-spacing: 0.1px;
        }

        .main-footer strong {
            color: white;
        }

        .main-footer a {
            color: var(--primary-yellow);
            text-decoration: none;
        }

        .main-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 767px) {
            .content .container-fluid {
                padding-left: 0.7rem;
                padding-right: 0.7rem;
            }

            .content-wrapper {
                margin-top: 54px;
                padding-bottom: 64px;
            }

            .content-header {
                padding: 0.7rem 0 0.6rem;
            }

            .content-header h1 {
                font-size: 1.3rem;
            }

            .main-header.navbar {
                min-height: 54px;
            }

            .navbar-text {
                font-size: 0.84rem;
                gap: 0.4rem;
            }

            .navbar-user-name {
                max-width: 140px;
            }

            .card {
                border-radius: 0.8rem;
            }

            .card-header {
                padding: 0.75rem 0.85rem;
            }

            .card-body {
                padding: 0.85rem;
            }

            .table {
                font-size: 0.86rem;
            }

            .table td,
            .table th {
                padding-top: 0.55rem;
                padding-bottom: 0.55rem;
            }

            .table-responsive > .table {
                min-width: 680px;
            }

            .form-label {
                font-size: 0.84rem;
                margin-bottom: 0.3rem;
            }

            .form-control,
            .form-select {
                font-size: 0.9rem;
                padding: 0.42rem 0.62rem;
            }

            .btn {
                font-size: 0.83rem;
                padding: 0.38rem 0.62rem;
            }

            .badge {
                font-size: 0.72rem;
                padding: 0.38rem 0.55rem;
            }

            .main-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .main-footer .footer-right {
                margin-left: 0;
            }
        }

        @media (max-width: 480px) {
            .content .container-fluid {
                padding-left: 0.55rem;
                padding-right: 0.55rem;
            }

            .content-header h1 {
                font-size: 1.12rem;
            }

            .card-header,
            .card-body {
                padding-left: 0.7rem;
                padding-right: 0.7rem;
            }

            .table {
                font-size: 0.8rem;
            }

            .table td,
            .table th {
                padding-top: 0.46rem;
                padding-bottom: 0.46rem;
            }

            .btn {
                font-size: 0.78rem;
                padding: 0.34rem 0.54rem;
            }

            .actions-inline {
                gap: 0.26rem;
            }

            .actions-inline .btn {
                padding: 0.28rem 0.44rem;
            }

            .actions-inline .btn i {
                margin-right: 0 !important;
            }

            .navbar-user-name {
                max-width: 100px;
                font-size: 0.78rem;
            }

            .navbar-role-badge {
                display: none;
            }

            .navbar-text .btn {
                font-size: 0.74rem;
                padding: 0.3rem 0.48rem;
            }

            .navbar-search-form .form-control,
            .navbar-search-form .btn {
                font-size: 0.78rem;
                padding-top: 0.34rem;
                padding-bottom: 0.34rem;
            }

            .content-wrapper {
                margin-top: 52px;
                min-height: calc(100vh - 52px);
            }
        }
    </style>

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-toggle-btn" id="menuToggleBtn" href="#" role="button" aria-label="Toggle sidebar" aria-expanded="true"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-flex align-items-center ms-1">
                    <a href="{{ route('dashboard') }}" class="navbar-brand-combined text-decoration-none">
                        <img src="{{ asset('images/logo-login.png') }}" alt="Logo Jajanan Cikampek" class="navbar-brand-logo">
                        <span class="navbar-brand-label">Jajanan Cikampek</span>
                    </a>
                </li>
            </ul>

            <form class="navbar-search-form d-flex align-items-center" method="GET" action="{{ route('dashboard.search') }}" role="search">
                <div class="input-group input-group-sm">
                    <input type="search" name="q" class="form-control" placeholder="Cari barang atau kode barang..." value="{{ request()->routeIs('dashboard.search') ? request('q') : '' }}" aria-label="Cari barang">
                    <button class="btn btn-light" type="submit">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
            </form>

            <div class="navbar-text ms-auto">
                <span class="navbar-user-name"><i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}</span>
                <span class="badge text-uppercase navbar-role-badge">{{ Auth::user()->role }}</span>
                <form method="POST" action="{{ route('logout') }}" class="navbar-logout-form">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light text-dark fw-600">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar elevation-4">
            <!-- Brand/Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link text-decoration-none">
                <img src="{{ asset('images/logo-login.png') }}" alt="Logo Jajanan Cikampek" class="brand-logo-image">
                <span class="brand-text ms-2">Jajanan Cikampek</span>
            </a>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('barang.index') }}" class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cube"></i>
                            <p>Data Barang</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('barang-masuk.index') }}" class="nav-link {{ request()->routeIs('barang-masuk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-arrow-down"></i>
                            <p>Barang Masuk</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('barang-keluar.index') }}" class="nav-link {{ request()->routeIs('barang-keluar.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-arrow-up"></i>
                            <p>Barang Keluar</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Laporan</p>
                        </a>
                    </li>

                    @if(auth()->user()?->isOwner())
                        <li class="nav-item">
                            <a href="{{ route('void-requests.index') }}" class="nav-link {{ request()->routeIs('void-requests.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-shield"></i>
                                <p>Approval Void</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('stok-opname.rekap') }}" class="nav-link {{ request()->routeIs('stok-opname.rekap') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>Rekap Cabang Harian</p>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()?->isKaryawan())
                        <li class="nav-item">
                            <a href="{{ route('stok-opname.index') }}" class="nav-link {{ request()->routeIs('stok-opname.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clipboard-check"></i>
                                <p>Stok Opname Harian</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0"><i class="fas fa-chevron-right me-2"></i>@yield('page-title')</h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Alert Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>Error!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="footer-left">
                <span class="footer-line">
                    <strong>Copyright &copy; {{ date('Y') }} <a href="{{ url('/') }}">Jajanan Cikampek</a>.</strong>
                </span>
                <span class="footer-line">Sistem Manajemen Stok Cerdas.</span>
            </div>
            <span class="footer-line"><b>Versi</b> 1.0.0 - Sekali Jajan, Jajan Teroos!!!</span>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButton = document.getElementById('menuToggleBtn');
            if (!toggleButton) return;

            const body = document.body;
            const mobileQuery = window.matchMedia('(max-width: 991.98px)');

            const updateToggleState = () => {
                const sidebarVisible = mobileQuery.matches
                    ? body.classList.contains('sidebar-open')
                    : !body.classList.contains('sidebar-full-hide');

                toggleButton.setAttribute('aria-expanded', String(sidebarVisible));
            };

            toggleButton.addEventListener('click', function (event) {
                event.preventDefault();

                if (mobileQuery.matches) {
                    body.classList.toggle('sidebar-open');
                    body.classList.remove('sidebar-full-hide');
                    body.classList.remove('sidebar-collapse');
                } else {
                    body.classList.toggle('sidebar-full-hide');
                    body.classList.remove('sidebar-open');
                    body.classList.remove('sidebar-collapse');
                }

                updateToggleState();
            });

            window.addEventListener('resize', function () {
                if (!mobileQuery.matches) {
                    body.classList.remove('sidebar-open');
                }
                updateToggleState();
            });

            updateToggleState();
        });
    </script>

    @stack('scripts')
</body>
</html>
