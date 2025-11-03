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
            background-color: #f8faff;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #006d77 0%, #118ab2 100%);
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            width: 70px;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1050;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar:hover,
        .sidebar.active {
            width: 240px;
        }

        .sidebar h5 {
            font-weight: 600;
            font-size: 18px;
            padding: 20px 0;
            text-align: center;
            letter-spacing: 0.5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sidebar:hover h5,
        .sidebar.active h5 {
            opacity: 1;
        }

        .sidebar h5 span {
            display: block;
            font-size: 13px;
            font-weight: 400;
            color: #cde3f5;
        }

        /* Sidebar Navigation */
        .sidebar .nav-link {
            color: #e9ecef;
            border-radius: 8px;
            padding: 10px 15px;
            margin: 5px 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar:hover .nav-link,
        .sidebar.active .nav-link {
            justify-content: flex-start;
        }

        .sidebar .nav-link i {
            font-size: 18px;
            margin-right: 0;
            transition: margin 0.3s;
        }

        .sidebar:hover .nav-link i,
        .sidebar.active .nav-link i {
            margin-right: 12px;
        }

        .sidebar .nav-link span {
            display: none;
            transition: opacity 0.3s;
        }

        .sidebar:hover .nav-link span,
        .sidebar.active .nav-link span {
            display: inline;
        }

        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        /* Logout Button */
        .btn-logout {
            background-color: #ef476f;
            border: none;
            color: #fff;
            border-radius: 8px;
            padding: 10px 12px;
            margin: 10px;
            width: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
            cursor: pointer;
        }

        .btn-logout span {
            display: none;
        }

        .sidebar:hover .btn-logout,
        .sidebar.active .btn-logout {
            width: calc(100% - 20px);
            justify-content: center;
        }

        .sidebar:hover .btn-logout span,
        .sidebar.active .btn-logout span {
            display: inline;
        }

        .btn-logout:hover {
            background-color: #d63c62;
        }

        /* Main Content */
        .content {
            margin-left: 70px;
            width: calc(100% - 70px);
            padding: 20px;
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        .sidebar:hover ~ .content,
        .sidebar.active ~ .content {
            margin-left: 240px;
            width: calc(100% - 240px);
        }

        /* Topbar */
        .topbar {
            background: #ffffff;
            padding: 12px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .user-info {
            font-weight: 500;
            color: #343a40;
        }

        /* Toggle Button (Mobile) */
        .toggle-btn {
            background: none;
            border: none;
            color: #006d77;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Overlay for Mobile */
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1040;
        }

        .overlay.active {
            display: block;
        }

        @media (max-width: 992px) {
            .sidebar {
                left: -240px;
            }
            .sidebar.active {
                left: 0;
            }
            .content {
                margin-left: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body>
<div class="d-flex">

    <!-- Sidebar -->
    <nav class="sidebar p-3" id="sidebar">
        <div>
            <h5>
                Semen Padang Hospital
                <span>Admin Panel</span>
            </h5>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('karyawan.index') }}" class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        <span>Employees</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-clock"></i>
                        <span>Jadwal Kerja</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('absensi.index') }}" class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>Attendance</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-clock-history"></i>
                        <span>Overtime</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-calendar-event"></i>
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
        <div>
            <hr class="text-light">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout d-flex align-items-center justify-content-center">
                    <i class="bi bi-box-arrow-right"></i>
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
