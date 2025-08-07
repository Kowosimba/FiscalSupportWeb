<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favi.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <style>
        .password-field {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
            z-index: 10;
            background: none;
            border: none;
            font-size: 1rem;
        }
        .password-toggle:hover {
            color: #005a1d;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-check-input {
            margin: 0;
            accent-color: #005a1d;
        }
        .form-check-label {
            font-size: 0.9rem;
            color: #374151;
            font-weight: 500;
            cursor: pointer;
        }
        .forgot-link {
            color: #005a1d;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        .forgot-link:hover {
            color: #005a1d;
            text-decoration: underline;
        }
        .login-card {
            background: white;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            margin: 2rem auto;
        }
        .logo {
            max-width: 180px;
            height: auto;
        }
        .login-header h1 {
            color: #111827;
            font-weight: 700;
            font-size: 1.875rem;
            margin-bottom: 0.5rem;
        }
        .login-header p {
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        .login-btn {
            background: linear-gradient(135deg, #05531b, #006817);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            transition: all 0.2s ease;
            font-size: 1rem;
            position: relative;
            overflow: hidden;
        }
        .login-btn:hover {
            background: linear-gradient(135deg, #094512, #025a0f);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(8, 93, 28, 0.3);
            color: white;
        }
        .icon-right {
            transition: transform 0.2s ease;
        }
        .login-btn:hover .icon-right {
            transform: translateX(4px);
        }
        .register-text {
            color: #6b7280;
            font-size: 0.9rem;
        }
        .register-text a {
            color: #059669;
            text-decoration: none;
            font-weight: 600;
        }
        .register-text a:hover {
            color: #005a1d;
            text-decoration: underline;
        }
        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: #f9fafb;
        }
        .form-control:focus {
            border-color: #005a1d;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            background: white;
        }
        .form-control.is-invalid {
            border-color: #dc2626;
        }
        .alert {
            border-radius: 12px;
            border: none;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background: rgba(5, 150, 105, 0.1);
            color: #047857;
            border-left: 4px solid #059669;
        }
        .alert-danger {
            background: rgba(220, 38, 38, 0.1);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        body {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }
        @media (max-width: 576px) {
            .login-card {
                margin: 1rem;
                padding: 1.5rem;
            }
            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/logo/logo-v2.png') }}" alt="Company Logo" class="logo">
            </div>
            
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <div class="login-header text-center">
                <h1>Welcome Back</h1>
                <p>Sign in to access your dashboard</p>
            </div>

            <form action="{{ route('login') }}" method="POST" id="loginForm">
                @csrf
                <div class="mb-3">
                    <label for="email" class="visually-hidden">Email address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" placeholder="Email address" 
                           required value="{{ old('email') }}" autocomplete="username">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="visually-hidden">Password</label>
                    <div class="password-field">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" placeholder="Password" 
                               required autocomplete="current-password">
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn login-btn w-100">
                    Sign In
                    <i class="fas fa-sign-in-alt ms-2 icon-right"></i>
                </button>

                <div class="register-text text-center mt-3">
                    Don't have an account? <a href="{{ route('show.register') }}">Create one</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
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

        // Add form loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const icon = submitBtn.querySelector('.icon-right');
            
            submitBtn.disabled = true;
            icon.className = 'fas fa-spinner fa-spin ms-2 icon-right';
            submitBtn.innerHTML = submitBtn.innerHTML.replace('Sign In', 'Signing In...');
        });

        // Auto-focus email field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>