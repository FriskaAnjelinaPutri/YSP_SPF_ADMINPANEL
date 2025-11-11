<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Semen Padang Hospital | Admin Panel')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo sph.png') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0fdf4;
            overflow-x: hidden;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 280px;
            background-color: #ffffff;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            z-index: 1050;
            display: flex;
            flex-direction: column;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-header img {
            width: 50px;
            height: 50px;
            margin-bottom: 0.75rem;
        }

        .sidebar-header h5 {
            font-weight: 600;
            font-size: 1.1rem;
            color: #166534;
            line-height: 1.3;
            margin: 0;
        }

        .sidebar-header span {
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 400;
        }

        /* Sidebar Navigation - Scrollable */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1rem 0.75rem;
        }

        /* Custom Scrollbar */
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Nav Items */
        .sidebar .nav-link {
            color: #374151;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
            color: #6b7280;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link:hover {
            background-color: #f0fdf4;
            color: #166534;
        }

        .sidebar .nav-link.active {
            background-color: #dcfce7;
            color: #166534;
            font-weight: 600;
        }

        .sidebar .nav-link.active i,
        .sidebar .nav-link:hover i {
            color: #16a34a;
        }

        /* Submenu */
        .submenu {
            margin-left: 0;
            padding-left: 2.5rem;
        }

        .submenu .nav-link {
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
        }

        .submenu .nav-link i {
            font-size: 0.9rem;
        }

        /* Collapse Arrow */
        .collapse-arrow {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }

        .nav-link[aria-expanded="true"] .collapse-arrow {
            transform: rotate(180deg);
        }

        /* Sidebar Footer - Logout */
        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid #e5e7eb;
            flex-shrink: 0;
        }

        .btn-logout {
            background-color: transparent;
            border: 1px solid #e5e7eb;
            color: #374151;
            border-radius: 0.5rem;
            padding: 0.75rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .btn-logout:hover {
            background-color: #fee2e2;
            color: #dc2626;
            border-color: #fca5a5;
        }

        .btn-logout i {
            font-size: 1.1rem;
        }

        /* ============ MAIN CONTENT ============ */
        .content {
            margin-left: 280px;
            width: calc(100% - 280px);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Topbar */
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
            color: #166534;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .user-info i {
            font-size: 1.75rem;
            color: #16a34a;
        }

        /* ============ COLLAPSED STATE ============ */
        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .sidebar-header h5,
        .sidebar.collapsed .sidebar-header span,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .btn-logout span {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 0.75rem;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.3rem;
        }

        .sidebar.collapsed .submenu {
            display: none;
        }

        .sidebar.collapsed .collapse-arrow {
            display: none;
        }

        .content.collapsed {
            margin-left: 80px;
            width: calc(100% - 80px);
            transition: height 0.3s ease;
        }

        .sidebar .collapse.show {
            height: auto;
        }

        /* ============ MOBILE OVERLAY ============ */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            transition: opacity 0.3s ease;
        }

        .overlay.active {
            display: block;
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 992px) {
            .sidebar {
                left: -280px;
            }

            .sidebar.active {
                left: 0;
                box-shadow: 0 0 50px rgba(0, 0, 0, 0.3);
            }

            .content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .toggle-btn-desktop {
                display: none !important;
            }
        }

        @media (min-width: 993px) {
            .toggle-btn-mobile {
                display: none !important;
            }
        }

        /* ============ BADGES ============ */
        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 12px;
            font-weight: 500;
        }

        .badge-success {
            background-color: #22c55e;
            color: #fff;
        }

        .badge-warning {
            background-color: #f59e0b;
            color: #fff;
        }

        .badge-danger {
            background-color: #ef4444;
            color: #fff;
        }

        .badge-info {
            background-color: #3b82f6;
            color: #fff;
        }

        .badge-primary {
            background-color: #8b5cf6;
            color: #fff;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div class="d-flex">
        <!-- ============ SIDEBAR ============ -->
        <nav class="sidebar" id="sidebar">
            <!-- Header -->
            <div class="sidebar-header">
                <img src="{{ asset('logo sph.png') }}" alt="Logo">
                <h5>
                    Semen Padang Hospital
                    <br>
                    <span>Admin Panel</span>
                </h5>
            </div>

            <!-- Navigation -->
            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('karyawan.index') }}"
                            class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Karyawan</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('absensi.index') }}"
                            class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Absensi</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('lembur.index') }}"
                            class="nav-link {{ request()->routeIs('lembur.*') ? 'active' : '' }}">
                            <i class="bi bi-clock-fill"></i>
                            <span>Lembur</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('cuti.index') }}"
                            class="nav-link {{ request()->routeIs('cuti.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-x-fill"></i>
                            <span>Cuti</span>
                        </a>
                    </li>

                    <!-- Jadwal Submenu -->
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center justify-content-between {{ request()->routeIs('jadwal.*') || request()->routeIs('pola.*') || request()->routeIs('tipe.*') ? 'active' : '' }}"
                            href="#" role="button" id="jadwalDropdown">
                            <div>
                                <i class="bi bi-calendar-week-fill me-2"></i>
                                <span>Manajemen Jadwal</span>
                            </div>
                            <i class="bi bi-chevron-down collapse-arrow ms-auto"></i>
                        </a>

                        <div class="collapse {{ request()->routeIs('jadwal.*') || request()->routeIs('pola.*') || request()->routeIs('tipe.*') ? 'show' : '' }}"
                            id="jadwalSubmenu">
                            <ul class="submenu list-unstyled ps-3">
                                <li>
                                    <a href="{{ route('jadwal.index') }}"
                                        class="nav-link {{ request()->routeIs('jadwal.index') ? 'active' : '' }}">
                                        <i class="bi bi-clock"></i>
                                        <span>Master Jadwal</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('pola.index') }}"
                                        class="nav-link {{ request()->routeIs('pola.*') ? 'active' : '' }}">
                                        <i class="bi bi-arrow-repeat"></i>
                                        <span>Pola Kerja</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tipe.index') }}"
                                        class="nav-link {{ request()->routeIs('tipe.*') ? 'active' : '' }}">
                                        <i class="bi bi-tags"></i>
                                        <span>Tipe Shift</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('jadwal.generate.create') }}"
                                        class="nav-link {{ request()->routeIs('jadwal.generate.create') ? 'active' : '' }}">
                                        <i class="bi bi-magic"></i>
                                        <span>Generate Jadwal</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('jadwal.hasil') }}"
                                        class="nav-link {{ request()->routeIs('jadwal.hasil') ? 'active' : '' }}">
                                        <i class="bi bi-table"></i>
                                        <span>Hasil Generate</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>


                    {{-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-headset"></i>
                            <span>Helpdesk</span>
                        </a>
                    </li> --}}
                </ul>
            </div>

            <!-- Footer - Logout -->
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

        <!-- Overlay (Mobile) -->
        <div class="overlay" id="overlay"></div>

        <!-- ============ MAIN CONTENT ============ -->
        <main class="content" id="content">
            <!-- Topbar -->
            <div class="topbar">
                <div>
                    <button class="toggle-btn toggle-btn-mobile" id="toggleSidebarMobile">
                        <i class="bi bi-list"></i>
                    </button>
                    <button class="toggle-btn toggle-btn-desktop" id="toggleSidebarDesktop">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ session('user')['name'] ?? 'Admin' }}</span>
                </div>
            </div>

            <!-- Page Content -->
            <div class="p-4">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const overlay = document.getElementById('overlay');
            const toggleBtnMobile = document.getElementById('toggleSidebarMobile');
            const toggleBtnDesktop = document.getElementById('toggleSidebarDesktop');

            // === DROPDOWN CUSTOM CONTROL ===
            const dropdownTrigger = document.getElementById('jadwalDropdown');
            const dropdownMenu = document.getElementById('jadwalSubmenu');
            const arrow = dropdownTrigger.querySelector('.collapse-arrow');

            dropdownTrigger.addEventListener('click', function(e) {
                e.preventDefault();

                const isOpen = dropdownMenu.classList.contains('show');

                // Close all other dropdowns (if you add more later)
                document.querySelectorAll('.sidebar .collapse').forEach(menu => {
                    if (menu !== dropdownMenu) {
                        menu.classList.remove('show');
                        const siblingArrow = menu.previousElementSibling.querySelector(
                            '.collapse-arrow');
                        if (siblingArrow) siblingArrow.style.transform = 'rotate(0deg)';
                    }
                });

                // Toggle current
                dropdownMenu.classList.toggle('show');
                arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            });

            // Close dropdown when clicking outside (optional)
            document.addEventListener('click', function(e) {
                if (!sidebar.contains(e.target)) {
                    dropdownMenu.classList.remove('show');
                    arrow.style.transform = 'rotate(0deg)';
                }
            });

            // === SIDEBAR TOGGLE (Desktop & Mobile) ===
            const savedState = localStorage.getItem('sidebarState');
            if (window.innerWidth > 992 && savedState === 'collapsed') {
                sidebar.classList.add('collapsed');
                content.classList.add('collapsed');
            }

            if (toggleBtnDesktop) {
                toggleBtnDesktop.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    content.classList.toggle('collapsed');
                    const state = sidebar.classList.contains('collapsed') ? 'collapsed' : 'expanded';
                    localStorage.setItem('sidebarState', state);
                });
            }

            if (toggleBtnMobile) {
                toggleBtnMobile.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                    document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }

            // === Fix Bootstrap Collapse Conflict (Optional Backup) ===
            const bsCollapses = document.querySelectorAll('.sidebar .collapse');
            bsCollapses.forEach(collapse => {
                collapse.addEventListener('show.bs.collapse', function() {
                    bsCollapses.forEach(other => {
                        if (other !== collapse && other.classList.contains('show')) {
                            const bsCollapse = bootstrap.Collapse.getInstance(other);
                            if (bsCollapse) bsCollapse.hide();
                        }
                    });
                });
            });
        });
    </script>


    @yield('scripts')
</body>

</html>
