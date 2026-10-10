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

        /* =========================================================
   TOAST NOTIFICATION
========================================================= */

        .toast-container {
            position: fixed;
            top: 82px;
            right: 24px;
            z-index: 5000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: min(390px, calc(100vw - 32px));
            pointer-events: none;
        }

        .app-toast {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            width: 100%;
            padding: 15px 16px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow:
                0 18px 45px rgba(15, 23, 42, 0.14),
                0 2px 8px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            pointer-events: auto;

            opacity: 0;
            transform: translateX(24px);
            animation: toast-in 0.28s ease forwards;
        }

        .app-toast.toast-hide {
            animation: toast-out 0.25s ease forwards;
        }

        .toast-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .toast-content {
            flex: 1;
            min-width: 0;
        }

        .toast-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 3px;
        }

        .toast-message {
            color: var(--muted);
            font-size: 13.5px;
            line-height: 1.5;
            word-break: break-word;
        }

        .toast-close {
            width: 28px;
            height: 28px;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #94a3b8;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            flex-shrink: 0;
        }

        .toast-close:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .toast-progress {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 3px;
            width: 100%;
            transform-origin: left;
            animation: toast-progress 4s linear forwards;
        }

        /* SUCCESS */

        .app-toast.toast-success {
            border-left: 4px solid #16a34a;
        }

        .toast-success .toast-icon {
            background: #dcfce7;
            color: #15803d;
        }

        .toast-success .toast-progress {
            background: #16a34a;
        }

        /* ERROR */

        .app-toast.toast-error {
            border-left: 4px solid #dc2626;
        }

        .toast-error .toast-icon {
            background: #fee2e2;
            color: #b91c1c;
        }

        .toast-error .toast-progress {
            background: #dc2626;
        }

        /* WARNING */

        .app-toast.toast-warning {
            border-left: 4px solid #f59e0b;
        }

        .toast-warning .toast-icon {
            background: #fef3c7;
            color: #b45309;
        }

        .toast-warning .toast-progress {
            background: #f59e0b;
        }

        /* INFO */

        .app-toast.toast-info {
            border-left: 4px solid #0284c7;
        }

        .toast-info .toast-icon {
            background: #e0f2fe;
            color: #0369a1;
        }

        .toast-info .toast-progress {
            background: #0284c7;
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateX(24px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toast-out {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(24px);
            }
        }

        @keyframes toast-progress {
            from {
                transform: scaleX(1);
            }

            to {
                transform: scaleX(0);
            }
        }


        /* =========================================================
   CONFIRMATION MODAL
========================================================= */

        .confirm-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 6000;
            background: rgba(15, 23, 42, 0.48);
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;

            opacity: 0;
            visibility: hidden;
            transition:
                opacity 0.2s ease,
                visibility 0.2s ease;
        }

        .confirm-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .confirm-modal {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 18px;

            box-shadow:
                0 24px 70px rgba(15, 23, 42, 0.22),
                0 4px 16px rgba(15, 23, 42, 0.08);

            padding: 24px;

            transform: scale(0.96) translateY(8px);
            transition: transform 0.2s ease;
        }

        .confirm-modal-overlay.show .confirm-modal {
            transform: scale(1) translateY(0);
        }

        .confirm-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 13px;

            display: flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 17px;

            background: #fee2e2;
            color: #b91c1c;

            font-size: 23px;
            font-weight: 700;
        }

        .confirm-modal.confirm-warning .confirm-icon-wrapper {
            background: #fef3c7;
            color: #b45309;
        }

        .confirm-modal.confirm-info .confirm-icon-wrapper {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .confirm-title {
            font-size: 19px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .confirm-message {
            font-size: 14px;
            line-height: 1.6;
            color: var(--muted);
        }

        .confirm-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .confirm-button {
            min-width: 88px;
        }

        .confirm-button-danger {
            background: #dc2626;
            color: #ffffff;
        }

        .confirm-button-danger:hover {
            background: #b91c1c;
        }

        .confirm-button-warning {
            background: #f59e0b;
            color: #ffffff;
        }

        .confirm-button-warning:hover {
            background: #d97706;
        }

        .confirm-button-primary {
            background: var(--primary);
            color: #ffffff;
        }

        .confirm-button-primary:hover {
            background: var(--primary-dark);
        }

        body.modal-open {
            overflow: hidden;
        }


        /* ALERT LAMA UNTUK VALIDASI DI DALAM HALAMAN */

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


        @media (max-width: 600px) {
            .toast-container {
                top: 74px;
                right: 16px;
                left: 16px;
                width: auto;
            }

            .confirm-modal {
                padding: 20px;
            }

            .confirm-actions {
                flex-direction: column-reverse;
            }

            .confirm-actions .btn {
                width: 100%;
            }
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

                <a href="{{ route('tahun-ajaran.index') }}"
                    class="{{ request()->is('tahun-ajaran*') ? 'active' : '' }}">
                    Tahun Ajaran
                </a>

                <a href="{{ route('riwayat-kelas.index') }}"
                    class="{{ request()->is('riwayat-kelas*') ? 'active' : '' }}">
                    Riwayat Kelas
                </a>

                <a href="{{ route('mutasi.index') }}" class="{{ request()->is('mutasi*') ? 'active' : '' }}">
                    Mutasi Siswa
                </a>

                <a href="{{ route('alumni.index') }}" class="{{ request()->is('alumni*') ? 'active' : '' }}">
                    Alumni
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
                @yield('content')
            </section>
        </main>
    </div>

    {{-- =====================================================
    GLOBAL TOAST NOTIFICATION
    ===================================================== --}}

    <div class="toast-container" id="toastContainer">

        @if (session('success'))
            <div class="app-toast toast-success" data-toast>
                <div class="toast-icon">
                    ✓
                </div>

                <div class="toast-content">
                    <div class="toast-title">
                        Berhasil
                    </div>

                    <div class="toast-message">
                        {{ session('success') }}
                    </div>
                </div>

                <button type="button" class="toast-close" aria-label="Tutup" onclick="closeToast(this)">
                    ×
                </button>

                <div class="toast-progress"></div>
            </div>
        @endif


        @if (session('error'))
            <div class="app-toast toast-error" data-toast>
                <div class="toast-icon">
                    !
                </div>

                <div class="toast-content">
                    <div class="toast-title">
                        Gagal
                    </div>

                    <div class="toast-message">
                        {{ session('error') }}
                    </div>
                </div>

                <button type="button" class="toast-close" aria-label="Tutup" onclick="closeToast(this)">
                    ×
                </button>

                <div class="toast-progress"></div>
            </div>
        @endif


        @if (session('warning'))
            <div class="app-toast toast-warning" data-toast>
                <div class="toast-icon">
                    !
                </div>

                <div class="toast-content">
                    <div class="toast-title">
                        Perhatian
                    </div>

                    <div class="toast-message">
                        {{ session('warning') }}
                    </div>
                </div>

                <button type="button" class="toast-close" aria-label="Tutup" onclick="closeToast(this)">
                    ×
                </button>

                <div class="toast-progress"></div>
            </div>
        @endif

    </div>

    {{-- =====================================================
    GLOBAL CONFIRMATION MODAL
    ===================================================== --}}

    <div class="confirm-modal-overlay" id="confirmModalOverlay" aria-hidden="true">
        <div class="confirm-modal" id="confirmModal" role="dialog" aria-modal="true"
            aria-labelledby="confirmModalTitle">

            <div class="confirm-icon-wrapper" id="confirmModalIcon">
                !
            </div>

            <div class="confirm-title" id="confirmModalTitle">
                Konfirmasi
            </div>

            <div class="confirm-message" id="confirmModalMessage">
                Apakah Anda yakin ingin melanjutkan?
            </div>

            <div class="confirm-actions">

                <button type="button" class="btn btn-secondary confirm-button" id="confirmCancelButton">
                    Batal
                </button>

                <button type="button" class="btn confirm-button confirm-button-danger" id="confirmOkButton">
                    Lanjutkan
                </button>

            </div>
        </div>
    </div>

    <script>
        /*
        |--------------------------------------------------------------------------
        | SIDEBAR
        |--------------------------------------------------------------------------
        */

        const menuToggle = document.getElementById('menuToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (menuToggle) {
            menuToggle.addEventListener('click', function () {
                if (window.innerWidth <= 768) {
                    document.body.classList.toggle('sidebar-open');
                } else {
                    document.body.classList.toggle('sidebar-collapsed');
                }
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
                document.body.classList.remove('sidebar-open');
            });
        }


        /*
        |--------------------------------------------------------------------------
        | TOAST NOTIFICATION
        |--------------------------------------------------------------------------
        */

        function closeToast(button) {
            const toast = button.closest('.app-toast');

            if (!toast) {
                return;
            }

            hideToast(toast);
        }


        function hideToast(toast) {
            if (!toast || toast.classList.contains('toast-hide')) {
                return;
            }

            toast.classList.add('toast-hide');

            setTimeout(function () {
                toast.remove();
            }, 250);
        }


        function setupToast(toast) {
            setTimeout(function () {
                hideToast(toast);
            }, 4000);
        }


        document
            .querySelectorAll('[data-toast]')
            .forEach(function (toast) {
                setupToast(toast);
            });



        window.showToast = function (
            message,
            type = 'success',
            title = null
        ) {
            const container = document.getElementById(
                'toastContainer'
            );

            if (!container) {
                return;
            }

            const config = {
                success: {
                    title: 'Berhasil',
                    icon: '✓'
                },

                error: {
                    title: 'Gagal',
                    icon: '!'
                },

                warning: {
                    title: 'Perhatian',
                    icon: '!'
                },

                info: {
                    title: 'Informasi',
                    icon: 'i'
                }
            };

            const selected =
                config[type] || config.info;

            const toast = document.createElement('div');

            toast.className =
                'app-toast toast-' + type;

            toast.setAttribute(
                'data-toast',
                ''
            );

            const icon = document.createElement('div');
            icon.className = 'toast-icon';
            icon.textContent = selected.icon;

            const content =
                document.createElement('div');

            content.className =
                'toast-content';

            const toastTitle =
                document.createElement('div');

            toastTitle.className =
                'toast-title';

            toastTitle.textContent =
                title || selected.title;

            const toastMessage =
                document.createElement('div');

            toastMessage.className =
                'toast-message';

            toastMessage.textContent =
                message;

            content.appendChild(
                toastTitle
            );

            content.appendChild(
                toastMessage
            );

            const closeButton =
                document.createElement('button');

            closeButton.type = 'button';

            closeButton.className =
                'toast-close';

            closeButton.setAttribute(
                'aria-label',
                'Tutup'
            );

            closeButton.textContent = '×';

            closeButton.addEventListener(
                'click',
                function () {
                    hideToast(toast);
                }
            );

            const progress =
                document.createElement('div');

            progress.className =
                'toast-progress';

            toast.appendChild(icon);
            toast.appendChild(content);
            toast.appendChild(closeButton);
            toast.appendChild(progress);

            container.appendChild(toast);

            setupToast(toast);
        };


        /*
        |--------------------------------------------------------------------------
        | GLOBAL CONFIRMATION MODAL
        |--------------------------------------------------------------------------
        */

        const confirmModalOverlay =
            document.getElementById(
                'confirmModalOverlay'
            );

        const confirmModal =
            document.getElementById(
                'confirmModal'
            );

        const confirmModalTitle =
            document.getElementById(
                'confirmModalTitle'
            );

        const confirmModalMessage =
            document.getElementById(
                'confirmModalMessage'
            );

        const confirmModalIcon =
            document.getElementById(
                'confirmModalIcon'
            );

        const confirmCancelButton =
            document.getElementById(
                'confirmCancelButton'
            );

        const confirmOkButton =
            document.getElementById(
                'confirmOkButton'
            );

        let confirmResolver = null;


        window.showConfirm = function ({
            title = 'Konfirmasi',
            message = 'Apakah Anda yakin ingin melanjutkan?',
            confirmText = 'Lanjutkan',
            cancelText = 'Batal',
            type = 'danger'
        } = {}) {

            return new Promise(function (resolve) {

                confirmResolver = resolve;

                confirmModalTitle.textContent =
                    title;

                confirmModalMessage.textContent =
                    message;

                confirmOkButton.textContent =
                    confirmText;

                confirmCancelButton.textContent =
                    cancelText;


                /*
                |-----------------------------------------
                | RESET TYPE
                |-----------------------------------------
                */

                confirmModal.classList.remove(
                    'confirm-warning',
                    'confirm-info'
                );

                confirmOkButton.classList.remove(
                    'confirm-button-danger',
                    'confirm-button-warning',
                    'confirm-button-primary'
                );


                /*
                |-----------------------------------------
                | APPLY TYPE
                |-----------------------------------------
                */

                if (type === 'warning') {

                    confirmModal.classList.add(
                        'confirm-warning'
                    );

                    confirmOkButton.classList.add(
                        'confirm-button-warning'
                    );

                    confirmModalIcon.textContent =
                        '!';

                } else if (type === 'info') {

                    confirmModal.classList.add(
                        'confirm-info'
                    );

                    confirmOkButton.classList.add(
                        'confirm-button-primary'
                    );

                    confirmModalIcon.textContent =
                        'i';

                } else {

                    confirmOkButton.classList.add(
                        'confirm-button-danger'
                    );

                    confirmModalIcon.textContent =
                        '!';
                }


                confirmModalOverlay.classList.add(
                    'show'
                );

                confirmModalOverlay.setAttribute(
                    'aria-hidden',
                    'false'
                );

                document.body.classList.add(
                    'modal-open'
                );

                setTimeout(function () {
                    confirmOkButton.focus();
                }, 100);
            });
        };


        function closeConfirmModal(result) {

            confirmModalOverlay.classList.remove(
                'show'
            );

            confirmModalOverlay.setAttribute(
                'aria-hidden',
                'true'
            );

            document.body.classList.remove(
                'modal-open'
            );

            if (confirmResolver) {
                confirmResolver(result);
                confirmResolver = null;
            }
        }


        confirmOkButton.addEventListener(
            'click',
            function () {
                closeConfirmModal(true);
            }
        );


        confirmCancelButton.addEventListener(
            'click',
            function () {
                closeConfirmModal(false);
            }
        );


        confirmModalOverlay.addEventListener(
            'click',
            function (event) {

                if (
                    event.target ===
                    confirmModalOverlay
                ) {
                    closeConfirmModal(false);
                }
            }
        );


        document.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Escape' &&
                    confirmModalOverlay.classList.contains(
                        'show'
                    )
                ) {
                    closeConfirmModal(false);
                }
            }
        );


        document.addEventListener(
            'submit',
            async function (event) {

                const form =
                    event.target.closest(
                        'form[data-confirm]'
                    );

                if (!form) {
                    return;
                }

                /*
                 * Jika sudah dikonfirmasi,
                 * jangan tampilkan modal lagi.
                 */
                if (
                    form.dataset.confirmed ===
                    'true'
                ) {
                    return;
                }

                event.preventDefault();

                const confirmed =
                    await showConfirm({
                        title:
                            form.dataset
                                .confirmTitle ||
                            'Konfirmasi',

                        message:
                            form.dataset
                                .confirm,

                        confirmText:
                            form.dataset
                                .confirmButton ||
                            'Lanjutkan',

                        cancelText:
                            form.dataset
                                .confirmCancel ||
                            'Batal',

                        type:
                            form.dataset
                                .confirmType ||
                            'danger'
                    });

                if (!confirmed) {
                    return;
                }

                form.dataset.confirmed =
                    'true';

                form.requestSubmit();
            }
        );
    </script>
</body>

</html>