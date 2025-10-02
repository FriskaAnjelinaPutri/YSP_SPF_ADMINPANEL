<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Admin Panel') }}</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: #212529;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            transition: all 0.3s ease;
            box-shadow: 2px 0 6px rgba(0,0,0,0.1);
            z-index: 1050;
        }
        .sidebar h5 {
            font-weight: 600;
            font-size: 18px;
            padding: 15px 0;
        }
        .sidebar .nav-link {
            color: #adb5bd;
            border-radius: 8px;
            padding: 10px 15px;
            margin: 5px 10px;
            font-size: 15px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }
        .sidebar .nav-link i {
            font-size: 18px;
            margin-right: 12px;
        }
        .sidebar .nav-link.active,
        .sidebar .nav-link:hover {
            background: #0d6efd;
            color: #fff;
        }

        /* Collapsed Sidebar */
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed h5,
        .sidebar.collapsed .nav-link span {
            display: none;
        }
        .sidebar.collapsed .nav-link {
            justify-content: center;
        }
        .sidebar.collapsed .nav-link i {
            margin: 0;
        }

        /* Content */
        .content {
            margin-left: 240px;
            width: calc(100% - 240px);
            padding: 20px;
            transition: all 0.3s ease;
            min-height: 100vh;
            background: #f8f9fa;
        }
        .content.expanded {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* Topbar */
        .topbar {
            background: #fff;
            padding: 12px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .toggle-btn {
            background: transparent;
            border: none;
            color: #0d6efd;
            font-size: 22px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="d-flex">
    {{-- Sidebar --}}
    <nav class="sidebar p-3" id="sidebar">
        <h5 class="text-center">Admin Panel</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('karyawan.index') }}" class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> <span>Employees</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-calendar-check"></i> <span>Attendance</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-clock-history"></i> <span>Overtime</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-calendar-event"></i> <span>Leave</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="bi bi-headset"></i> <span>Helpdesk</span>
                </a>
            </li>
        </ul>
        <hr class="text-secondary">
        <ul class="nav flex-column">
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-danger w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- Main Content --}}
    <main class="content" id="content">
        {{-- Topbar --}}
        <div class="topbar">
            <button class="toggle-btn" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="d-flex align-items-center">
                <i class="bi bi-person-circle me-2"></i>
                <span>{{ session('user')['name'] ?? 'Admin' }}</span>
            </div>
        </div>

        {{-- Page Content --}}
        <div class="mt-3">
            @yield('content')
        </div>
    </main>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('collapsed');
        document.getElementById('content').classList.toggle('expanded');
    }
</script>
</body>
</html>
