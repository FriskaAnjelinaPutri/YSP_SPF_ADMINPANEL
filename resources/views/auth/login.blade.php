<!DOCTYPE html>
<html lang="en"> {{-- pakai bahasa Inggris --}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="height: 100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-dark text-white text-center">
                    <h5 class="mb-0">Admin Panel Login</h5>
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
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                                placeholder="Enter your password">
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
