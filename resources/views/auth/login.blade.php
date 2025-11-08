<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Staff Attendance | Admin Login</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* ===========================
           GLOBAL STYLE (match dashboard)
        ============================ */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0fdf4; /* same as dashboard */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===========================
           LOGIN CARD STYLE
        ============================ */
        .login-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* ===========================
           HEADER
        ============================ */
        .login-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .login-header img {
            width: 70px;
            margin-bottom: 0.5rem;
        }

        .login-header h4 {
            font-weight: 700;
            color: #166534; /* dark green like dashboard title */
        }

        .login-header p {
            color: #16a34a; /* main green */
            font-size: 14px;
            margin-bottom: 0;
        }

        /* ===========================
           FORM ELEMENTS
        ============================ */
        .form-label {
            font-weight: 500;
            color: #1f2937;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #22c55e;
            box-shadow: 0 0 6px rgba(34, 197, 94, 0.25);
        }

        .input-group-text {
            background: #f9fafb;
            border: 1px solid #d1d5db;
            border-left: none;
            border-radius: 0 10px 10px 0;
            color: #16a34a;
            cursor: pointer;
        }

        /* ===========================
           BUTTON STYLE
        ============================ */
        .btn-login {
            background-color: #16a34a; /* main green */
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #15803d;
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(22, 101, 52, 0.3);
        }

        /* ===========================
           ALERTS & FOOTER
        ============================ */
        .alert {
            font-size: 14px;
            border-radius: 10px;
        }

        .footer-text {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-top: 1.5rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <img src="https://cdn-icons-png.flaticon.com/512/2966/2966487.png" alt="Hospital Icon">
            <h4>Admin Panel Login</h4>
            <p>Employee Attendance System</p>
        </div>

        {{-- Session error --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    required
                    placeholder="Enter your email">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        required
                        placeholder="Enter your password">
                    <span class="input-group-text" onclick="togglePassword()" title="Show/Hide Password">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-login">Sign In</button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} Semen Padang Hospital
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Show/Hide Password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>
</body>
</html>
