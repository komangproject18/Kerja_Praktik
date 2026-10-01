<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Data Siswa SMK Xaverius Palembang')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-sekolah.png') }}">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --sidebar: #0f172a;
            --sidebar-hover: #1e293b;
            --bg: #f3f6fb;
            --card: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --muted: #64748b;
            --danger: #dc2626;
            --warning: #f59e0b;
            --info: #0284c7;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-size: 14px;
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background: var(--sidebar);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding: 22px 16px;
            transition: 0.25s ease;
            z-index: 1000;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 34px;
            padding: 6px 4px;
        }

        .sidebar-logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            padding: 5px;
            flex-shrink: 0;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.35;
            letter-spacing: 0.2px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .sidebar-menu a {
            color: #cbd5e1;
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 15px;
            display: block;
            transition: 0.2s ease;
        }

        .sidebar-menu a:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .sidebar-menu a.active {
            background: var(--primary);
            color: white;
            font-weight: 600;
        }

        /* MAIN */
        .main {
            margin-left: 260px;
            width: calc(100% - 260px);
            min-height: 100vh;
            transition: 0.25s ease;
        }

        .topbar {
            height: 64px;
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .menu-toggle {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 10px;
            background: #edf2f7;
            color: var(--text);
            font-size: 21px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .menu-toggle:hover {
            background: #e2e8f0;
        }

        .topbar-title {
            font-size: 22px;
            font-weight: 700;
        }

        .topbar-user {
            font-size: 15px;
            color: #334155;
            font-weight: 500;
        }

        .content {
            padding: 24px 28px;
        }

        /* HEADER */
        .page-header {
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--muted);
        }

        /* CARD DASHBOARD */
        .card-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 22px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            min-height: 112px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .card-label {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .card-value {
            font-size: 34px;
            font-weight: 700;
            color: var(--text);
        }

        /* BUTTON */
        .action-row,
        .form-footer,
        .page-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            line-height: 1.2;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: var(--text);
        }

        .btn-secondary:hover {
            background: #cbd5e1;
        }

        .btn-info {
            background: #e0f2fe;
            color: #0369a1;
        }

        .btn-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-sm {
            padding: 7px 10px;
            font-size: 13px;
        }

        /* TABLE */
        .table-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .table-header {
            padding: 18px 22px;
            font-size: 18px;
            font-weight: 700;
            border-bottom: 1px solid var(--border);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            color: #334155;
            font-size: 13px;
            font-weight: 700;
            text-align: left;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        td {
            font-size: 13.5px;
            padding: 13px 18px;
            border-bottom: 1px solid var(--border);
            color: #1e293b;
            vertical-align: middle;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .empty-data {
            text-align: center;
            color: var(--muted);
            padding: 22px;
        }

        /* BADGE */
        .badge {
            display: inline-block;
            background: #dbeafe;
            color: #1d4ed8;
            padding: 4px 9px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* FORM */
        .form-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .form-section-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 18px;
            color: var(--text);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-full {
            grid-column: 1 / -1;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 7px;
            color: #334155;
        }

        .form-control {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            padding: 10px 12px;
            font-size: 14px;
            outline: none;
            background: white;
            color: var(--text);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        textarea.form-control {
            min-height: 82px;
            resize: vertical;
        }

        .form-error {
            color: var(--danger);
            font-size: 12.5px;
            margin-top: 6px;
        }

        /* ALERT */
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        /* ACTION BUTTONS */
        .action-buttons {
            display: flex;
            gap: 7px;
            align-items: center;
            flex-wrap: wrap;
        }

        .inline-form {
            display: inline;
        }

        /* SIDEBAR COLLAPSE */
        .sidebar-overlay {
            display: none;
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        body.sidebar-collapsed .main {
            margin-left: 0;
            width: 100%;
        }

        /* RESPONSIVE */
        @media (max-width: 1100px) {
            .card-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.45);
                z-index: 999;
            }

            .content {
                padding: 20px;
            }

            .card-grid,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 0 18px;
            }

            .topbar-user {
                display: none;
            }
        }

        .table-header-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .kebab-wrapper {
            position: relative;
        }

        .kebab-button {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 10px;
            background: #f1f5f9;
            color: #0f172a;
            font-size: 22px;
            cursor: pointer;
            line-height: 1;
        }

        .kebab-button:hover {
            background: #e2e8f0;
        }

        .kebab-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 42px;
            min-width: 150px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.15);
            overflow: hidden;
            z-index: 20;
        }

        .kebab-menu.show {
            display: block;
        }

        .kebab-menu button {
            width: 100%;
            border: none;
            background: white;
            padding: 11px 14px;
            text-align: left;
            font-size: 14px;
            cursor: pointer;
            color: #0f172a;
        }

        .kebab-menu button:hover {
            background: #f8fafc;
        }

        .select-cell {
            display: none;
        }

        .selection-mode .select-cell {
            display: table-cell;
        }

        .checkbox-column {
            width: 48px;
            text-align: center;
        }

        .table-checkbox {
            width: 17px;
            height: 17px;
            cursor: pointer;
        }

        .selection-toolbar {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            background: #f8fafc;
        }

        .selection-mode .selection-toolbar {
            display: flex;
        }

        .selection-info {
            font-size: 14px;
            color: var(--muted);
        }

        .selection-info strong {
            color: var(--text);
        }

        .selection-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .bulk-hidden-inputs {
            display: none;
        }
    </style>

</head>

<body>
    <div class="app">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('assets/logo-sekolah.png') }}" alt="Logo Sekolah" class="sidebar-logo">

                <div class="sidebar-title">
                    Data Siswa <br>
                    SMK Xaverius Palembang
                </div>
            </div>
            <nav class="sidebar-menu">
                <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    Beranda
                </a>

                <a href="{{ url('/siswa') }}" class="{{ request()->is('siswa*') ? 'active' : '' }}">
                    Data Siswa
                </a>

                <a href="{{ url('/kelas') }}" class="{{ request()->is('kelas*') ? 'active' : '' }}">
                    Daftar Kelas
                </a>

                <a href="{{ route('mutasi.index') }}" class="{{ request()->is('mutasi*') ? 'active' : '' }}">
                    Mutasi Siswa
                </a>

                <a href="{{ route('statistik.index') }}" class="{{ request()->is('statistik*') ? 'active' : '' }}">
                    Statistik
                </a>

                <a href="{{ url('/export') }}" class="{{ request()->is('export*') ? 'active' : '' }}">
                    Export Excel
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="topbar">
                <div class="topbar-left">
                    <button type="button" class="menu-toggle" id="menuToggle">
                        ☰
                    </button>

                    <div class="topbar-title">
                        @yield('page_title', 'Beranda')
                    </div>
                </div>

                <div class="topbar-user">
                    Admin Tata Usaha
                </div>
            </header>

            <section class="content">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif
                @yield('content')
            </section>
        </main>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', function () {
            if (window.innerWidth <= 768) {
                document.body.classList.toggle('sidebar-open');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
            }
        });

        sidebarOverlay.addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });
    </script>
</body>

</html>