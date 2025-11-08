<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semen Padang Hospital | Admin Panel</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo sph.png') }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0fdf4;
            /* Light green background from dashboard */
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background-color: #ffffff;
            color: #1f2937;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            /* A bit wider for better spacing */
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            padding: 1.5rem;
        }

        /* Collapsed state for future use if needed, for now, it's always open on desktop */
        /* .sidebar.collapsed { width: 90px; } */

        .sidebar-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding: 0 0.5rem;
        }

        .sidebar-header img {
            height: 40px;
            width: 40px;
            margin-right: 0.75rem;
        }

        .sidebar-header h5 {
            font-weight: 600;
            font-size: 1.1rem;
            color: #166534;
            /* Dark green from dashboard */
            line-height: 1.2;
            margin-bottom: 0;
        }

        .sidebar-header h5 span {
            font-size: 0.8rem;
            color: #6b7280;
            font-weight: 400;
        }


        /* Sidebar Navigation */
        .sidebar .nav-link {
            color: #374151;
            /* Dark gray for text */
            border-radius: 0.5rem;
            padding: 0.8rem 1rem;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 1rem;
            width: 20px;
            text-align: center;
            color: #6b7280;
            transition: all 0.2s ease;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background-color: #dcfce7;
            /* Light green from dashboard */
            color: #166534;
            /* Dark green from dashboard */
        }

        .sidebar .nav-link.active i,
        .sidebar .nav-link:hover i {
            color: #16a34a;
            /* Accent green from dashboard */
        }

        /* Logout Button */
        .btn-logout {
            background-color: transparent;
            border: 1px solid #d1d5db;
            color: #374151;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            font-weight: 500;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn-logout:hover {
            background-color: #fca5a5;
            /* Light red for hover */
            color: #b91c1c;
            /* Dark red for hover */
            border-color: #fca5a5;
        }

        .btn-logout i {
            font-size: 1.2rem;
        }

        /* Main Content */
        .content {
            margin-left: 260px;
            /* Match sidebar width */
            width: calc(100% - 260px);
            padding: 0;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background: #ffffff;
            padding: 1rem 2.5rem;
            /* Match dashboard padding */
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .user-info {
            font-weight: 500;
            color: #374151;
        }

        .user-info i {
            font-size: 1.5rem;
            color: #16a34a;
        }

        /* Toggle Button (Mobile) */
        .toggle-btn {
            background: none;
            border: none;
            color: #166534;
            font-size: 1.75rem;
            cursor: pointer;
        }

        /* Overlay for Mobile */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1040;
        }

        .overlay.active {
            display: block;
        }

        @media (max-width: 992px) {
            .sidebar {
                left: -280px;
                /* A bit wider for mobile */
                width: 280px;
            }

            .sidebar.active {
                left: 0;
                box-shadow: 0 0 50px rgba(0, 0, 0, 0.2);
            }

            .content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .topbar {
                padding: 1rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="d-flex">

        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div>
                <div class="sidebar-header text-center">
                    <img src="{{ asset('logo sph.png') }}" alt="Logo" style="width: 50px; margin-bottom: 10px;">
                    <h5 style="font-weight: 600; font-size: 18px; line-height: 1.3;">
                        Semen Padang Hospital
                        <br>
                        <span style="font-size: 13px; font-weight: 500; color: #595a5a;">Admin Panel</span>
                    </h5>
                </div>


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
                            <span>Employees</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('absensi.index') }}"
                            class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                            <i class="bi bi-check-circle-fill"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('lembur.index') }}" class="nav-link">
                            <i class="bi bi-clock-fill"></i>
                            <span>Overtime</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-calendar-x-fill"></i>
                            <span>Leave</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="bi bi-headset"></i>
                            <span>Helpdesk</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Logout -->
            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-left"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        <!-- Overlay (mobile only) -->
        <div class="overlay" id="overlay"></div>

        <!-- Main Content -->
        <main class="content" id="content">
            <!-- Topbar -->
            <div class="topbar">
                <button class="toggle-btn d-lg-none" id="toggleSidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="d-flex align-items-center user-info">
                    <i class="bi bi-person-circle me-2"></i>
                    <span>{{ session('user')['name'] ?? 'Admin' }}</span>
                </div>
            </div>

            <!-- Page Content -->
            <div class="mt-3">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar Toggle for Mobile
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const toggleBtn = document.getElementById('toggleSidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>
</body>

</html>
