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
           GLOBAL & BACKGROUND STYLING
        ============================ */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #00b4d8, #0077b6);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Floating blurred background circles */
        .circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.5;
            animation: float 10s ease-in-out infinite alternate;
        }

        .circle.one {
            width: 350px;
            height: 350px;
            background: #90e0ef;
            top: -80px;
            left: -100px;
        }

        .circle.two {
            width: 400px;
            height: 400px;
            background: #caf0f8;
            bottom: -120px;
            right: -120px;
        }

        @keyframes float {
            from { transform: translateY(0); }
            to { transform: translateY(30px); }
        }

        /* ===========================
           LOGIN CARD STYLING
        ============================ */
        .login-card {
            position: relative;
            z-index: 5;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 25px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
            transition: all 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.25);
        }

        /* ===========================
           HEADER
        ============================ */
        .card-header {
            background: linear-gradient(135deg, #0077b6, #00b4d8);
            text-align: center;
            padding: 40px 20px;
        }

        .card-header img {
            width: 70px;
            margin-bottom: 10px;
            animation: bounce 3s infinite ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .card-header h4 {
            color: #fff;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .card-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        /* ===========================
           FORM STYLING
        ============================ */
        .card-body {
            padding: 35px 30px;
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 12px;
            border: 1px solid #d0d0d0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 8px rgba(0, 180, 216, 0.4);
        }

        .input-group-text {
            background-color: #fff;
            border: 1px solid #d0d0d0;
            border-left: none;
            border-radius: 0 12px 12px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .input-group-text:hover {
            background-color: #f1faff;
            color: #00b4d8;
        }

        /* ===========================
           BUTTON STYLING
        ============================ */
        .btn-login {
            background: linear-gradient(135deg, #0077b6, #00b4d8);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px;
            border-radius: 12px;
            width: 100%;
            box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0096c7, #0077b6);
            transform: scale(1.03);
            box-shadow: 0 8px 20px rgba(0, 119, 182, 0.4);
        }

        /* ===========================
           FOOTER TEXT
        ============================ */
        .footer-text {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-top: 20px;
        }

        .alert {
            font-size: 14px;
            border-radius: 12px;
        }
    </style>
</head>
<body>

    <!-- Background floating shapes -->
    <div class="circle one"></div>
    <div class="circle two"></div>

    <!-- Login card -->
    <div class="login-card">
        <div class="card-header">
            <img src="https://cdn-icons-png.flaticon.com/512/2966/2966487.png" alt="Hospital Icon">
            <h4>Admin Panel Login</h4>
            <p class="text-white-50 mb-0">Employee Attendance System</p>
        </div>

        <div class="card-body p-4">
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

            <div class="footer-text mt-3">
                &copy; {{ date('Y') }} Semen Padang Hospital
            </div>
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
