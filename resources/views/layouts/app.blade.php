<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Semen Padang Hospital | Admin Panel')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo sph.png') }}">

    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #166534;
            --primary-light: #dcfce7;
            --primary-hover: #16a34a;
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0fdf4;
            overflow-x: hidden;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: #ffffff;
            box-shadow: 2px 0 8px rgba(0,0,0,0.08);
            transition: width 0.35s ease;
            z-index: 1050;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed { width: var(--sidebar-collapsed); }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
        }

        .sidebar-header img { width: 50px; height: 50px; margin-bottom: 0.75rem; }
        .sidebar-header h5 { font-weight: 600; color: var(--primary); margin: 0; line-height: 1.3; }
        .sidebar-header span { font-size: 0.8rem; color: #6b7280; }

        /* Scrollbar Premium */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem 0.75rem;
            scrollbar-width: thin;
            scrollbar-color: #94a3b8 transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; border-radius: 10px; }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: content-box;
            transition: background-color 0.3s ease;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover,
        .sidebar:hover .sidebar-nav::-webkit-scrollbar-thumb { background-color: #94a3b8; }
        .sidebar:hover .sidebar-nav::-webkit-scrollbar-track { background: #f1f5f9; }

        /* Nav Links */
        .sidebar .nav-link {
            color: #374151;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.35rem;
            font-weight: 500;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .sidebar .nav-link i {
            font-size: 1.15rem;
            margin-right: 0.75rem;
            width: 24px;
            text-align: center;
            color: #6b7280;
            transition: color 0.25s ease;
        }

        .sidebar .nav-link:hover { background: #f0fdf4; color: var(--primary); }
        .sidebar .nav-link:hover i,
        .sidebar .nav-link.active i { color: var(--primary-hover); }

        .sidebar .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        /* Submenu Indent */
        .submenu-indent {
            margin-left: 3.2rem;
            font-size: 0.925rem;
        }

        .submenu-indent .nav-link {
            padding: 0.65rem 1rem;
            border-radius: 0.4rem;
        }

        /* Collapsed State */
        .sidebar.collapsed .sidebar-header h5,
        .sidebar.collapsed .sidebar-header span,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .btn-logout span { display: none; }

        .sidebar.collapsed .nav-link { justify-content: center; padding: 0.9rem; }
        .sidebar.collapsed .nav-link i { margin-right: 0; font-size: 1.4rem; }
        .sidebar.collapsed .submenu-indent { display: none; }

        /* Footer */
        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-logout {
            width: 100%;
            background: transparent;
            border: 1px solid #e5e7eb;
            color: #374151;
            border-radius: 0.5rem;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .btn-logout:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

        /* ============ CONTENT ============ */
        .content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: all 0.35s ease;
        }

        .content.collapsed {
            margin-left: var(--sidebar-collapsed);
            width: calc(100% - var(--sidebar-collapsed));
        }

        .topbar {
            background: #ffffff;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: var(--primary);
            font-size: 1.6rem;
            cursor: pointer;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 500;
            color: #374151;
        }

        .user-info i { font-size: 1.9rem; color: var(--primary-hover); }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }

        .overlay.active { display: block; }

        @media (max-width: 992px) {
            .sidebar { left: -280px; }
            .sidebar.active { left: 0; box-shadow: 0 0 50px rgba(0,0,0,0.3); }
            .content, .content.collapsed { margin-left: 0 !important; width: 100% !important; }
            .toggle-btn-desktop { display: none !important; }
        }

        @media (min-width: 993px) {
            .toggle-btn-mobile { display: none !important; }
        }

        /* ===== ALIGN LOGO & TEXT (OVERRIDE AMAN) ===== */
        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .sidebar-brand img {
            margin-bottom: 0; /* override margin lama */
        }

        .sidebar-text {
            text-align: left;
            line-height: 1.2;
        }

        /* Ikut logika collapse yang sudah ada */
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div class="d-flex">
        <!-- SIDEBAR -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <img src="{{ asset('logo sph.png') }}" alt="Logo">
                    <div class="sidebar-text">
                        <h5>Semen Padang Hospital</h5>
                        <span>Admin Panel</span>
                    </div>
                </div>
            </div>
            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('karyawan.index') }}" class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Karyawan</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Pengguna</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('absensi.index') }}" class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Absensi</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('lembur.index') }}" class="nav-link {{ request()->routeIs('lembur.*') ? 'active' : '' }}">
                            <i class="bi bi-clock-fill"></i>
                            <span>Lembur</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('cuti.index') }}" class="nav-link {{ request()->routeIs('cuti.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-x-fill"></i>
                            <span>Cuti</span>
                        </a>
                    </li>

                    <!-- Manajemen Jadwal – SELALU TERBUKA & RAPI -->
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link active">
                            <i class="bi bi-calendar-week-fill"></i>
                            <span>Manajemen Jadwal</span>
                        </a>

                        <div class="submenu-indent">
                            <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                                <i class="bi bi-clock"></i> Master Jadwal
                            </a>
                            <a href="{{ route('pola.index') }}" class="nav-link {{ request()->routeIs('pola.*') ? 'active' : '' }}">
                                <i class="bi bi-arrow-repeat"></i> Pola Kerja
                            </a>
                            <a href="{{ route('tipe.index') }}" class="nav-link {{ request()->routeIs('tipe.*') ? 'active' : '' }}">
                                <i class="bi bi-tags"></i> Tipe Shift
                            </a>
                            <a href="{{ route('jadwal.generate.create') }}" class="nav-link {{ request()->routeIs('jadwal.generate.create') ? 'active' : '' }}">
                                <i class="bi bi-magic"></i> Generate Jadwal
                            </a>
                            <a href="{{ route('jadwal.hasil') }}" class="nav-link {{ request()->routeIs('jadwal.hasil') ? 'active' : '' }}">
                                <i class="bi bi-table"></i> Hasil Generate
                            </a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('helpdesk.index') }}" class="nav-link {{ request()->routeIs('helpdesk.*') ? 'active' : '' }}">
                            <i class="bi bi-headset"></i>
                            <span>Helpdesk</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Overlay Mobile -->
        <div class="overlay" id="overlay"></div>

        <!-- MAIN CONTENT -->
        <main class="content" id="content">
            <div class="topbar">
                <div>
                    <button class="toggle-btn toggle-btn-mobile" id="toggleMobile"><i class="bi bi-list"></i></button>
                    <button class="toggle-btn toggle-btn-desktop" id="toggleDesktop"><i class="bi bi-list" id="desktopIcon"></i></button>
                </div>
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ session('user')['name'] ?? 'Admin' }}</span>
                </div>
            </div>

            <div class="p-4 ">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar   = document.getElementById('sidebar');
            const content   = document.getElementById('content');
            const overlay   = document.getElementById('overlay');
            const toggleMob = document.getElementById('toggleMobile');
            const toggleDesk= document.getElementById('toggleDesktop');
            const deskIcon  = document.getElementById('desktopIcon');

            // Restore collapsed state
            if (window.innerWidth > 992 && localStorage.getItem('sidebarState') === 'collapsed') {
                sidebar.classList.add('collapsed');
                content.classList.add('collapsed');
                deskIcon.classList.replace('bi-list', 'bi-arrow-bar-right');
            }

            // Desktop toggle
            toggleDesk?.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('collapsed');
                const collapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarState', collapsed ? 'collapsed' : 'expanded');
                deskIcon.classList.toggle('bi-list', !collapsed);
                deskIcon.classList.toggle('bi-arrow-bar-right', collapsed);
            });

            // Mobile toggle
            toggleMob?.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Auto close mobile menu on link click
            document.querySelectorAll('.sidebar .nav-link[href]').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 992) {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
