<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Admin Panel</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo sph.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }
        .welcome-container {
            text-align: center;
            background-color: #ffffff;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .welcome-container h1 {
            color: #166534;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .welcome-container p {
            color: #4b5563;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .btn-login {
            background-color: #22c55e;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .btn-login:hover {
            background-color: #16a34a;
            color: white;
        }
    </style>
</head>
<body>
    <div class="welcome-container">
        <h1>Welcome to the Admin Panel</h1>
        <p>Please log in to access the system.</p>
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-login">
            Login
        </a>
    </div>
</body>
</html>
