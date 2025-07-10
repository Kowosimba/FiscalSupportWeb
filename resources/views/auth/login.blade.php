<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiscal Support Services | Secure Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favi.png') }}">
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/logo/logo-v2.png') }}" alt="Company Logo" class="logo">
        </div>
        
        <div class="login-header text-center">
            <h1>Welcome Back</h1>
            <p>Please enter your credentials to access your account</p>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <form id="loginForm" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="userEmail" class="visually-hidden">Email address</label>
                <input type="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       id="userEmail" 
                       name="email" 
                       placeholder="Email address" 
                       required 
                       autocomplete="username"
                       value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="userPassword" class="visually-hidden">Password</label>
                <div class="password-field">
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="userPassword" 
                           name="password" 
                           placeholder="Password" 
                           required 
                           autocomplete="current-password">
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberSession" name="remember">
                    <label class="form-check-label" for="rememberSession">Remember me</label>
                </div>
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn btn-primary login-btn w-100">
                <span class="btn-text">Sign In</span>
                <i class="fas fa-arrow-right ms-2 icon-right"></i>
            </button>
            
            <div class="signup-text text-center mt-3">
                Don't have an account? <a href="{{ route('show.register') }}">Sign up now</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('userPassword');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
