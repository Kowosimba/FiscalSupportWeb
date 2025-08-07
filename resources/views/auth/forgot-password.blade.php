<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favi.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Inter', sans-serif;
        }
        .forgot-card {
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
        .forgot-header h1 {
            color: #111827;
            font-weight: 700;
            font-size: 1.875rem;
            margin-bottom: 0.5rem;
        }
        .forgot-header p {
            color: #6b7280;
            font-size: 1rem;
            margin-bottom: 2rem;
            line-height: 1.5;
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
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1);
            background: white;
        }
        .reset-btn {
            background: linear-gradient(135deg, #059669, #10B981);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            transition: all 0.2s ease;
            font-size: 1rem;
        }
        .reset-btn:hover {
            background: linear-gradient(135deg, #047857, #059669);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.3);
            color: white;
        }
        .icon-right {
            transition: transform 0.2s ease;
        }
        .reset-btn:hover .icon-right {
            transform: translateX(4px);
        }
        .back-link {
            color: #6b7280;
            font-size: 0.9rem;
            text-decoration: none;
        }
        .back-link a {
            color: #059669;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link a:hover {
            color: #047857;
            text-decoration: underline;
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
        @media (max-width: 576px) {
            .forgot-card {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="forgot-card">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/logo/logo-v2.png') }}" alt="Company Logo" class="logo">
            </div>
            
            <div class="forgot-header text-center">
                <h1>Forgot Password?</h1>
                <p>Enter your email address and we'll send you a link to reset your password</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" id="resetForm">
                @csrf
                <div class="mb-4">
                    <label for="email" class="visually-hidden">Email address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" placeholder="Enter your email address" 
                           required value="{{ old('email') }}" autocomplete="email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn reset-btn w-100">
                    Send Reset Link
                    <i class="fas fa-paper-plane ms-2 icon-right"></i>
                </button>

                <div class="back-link text-center mt-3">
                    Remember your password? <a href="{{ route('show.login') }}">Sign in</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add form loading state
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const icon = submitBtn.querySelector('.icon-right');
            
            submitBtn.disabled = true;
            icon.className = 'fas fa-spinner fa-spin ms-2 icon-right';
            submitBtn.innerHTML = submitBtn.innerHTML.replace('Send Reset Link', 'Sending...');
        });

        // Auto-focus email field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>