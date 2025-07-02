{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Registration</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favi.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>
<body>
  
    <div class="register-card">
        <div class="text-center mb-4">
            <!-- Logo image like in the login page -->
            <img src="{{ asset('assets/img/logo/logo-v2.png') }}" alt="Company Logo" class="logo">
        </div>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}    
            </div>
        @endif
        <div class="register-header text-center">
            <h1>Create Your Account</h1>
            <p>Join us today to access all our services</p>
        </div>
        
        <form id="registerForm" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="fullName" class="visually-hidden">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="fullName" name="name" placeholder="Full Name" required value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="userEmail" class="visually-hidden">Email address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="userEmail" name="email" placeholder="Email address" required autocomplete="username" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="userPassword" class="visually-hidden">Password</label>
                <div class="password-field">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="userPassword" name="password" placeholder="Password" required autocomplete="new-password">
                   
                </div>
                <div class="password-strength"></div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="confirmPassword" class="visually-hidden">Confirm Password</label>
                <div class="password-field">
                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                   
                </div>
                <div class="password-match"></div>
            </div>
            @if ($errors->any())
        <div class="alert alert-danger mt-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
            
            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="acceptTerms" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                    <label class="form-check-label" for="acceptTerms">
                        I agree to the <a href="" class="terms-link">Terms of Service</a> and <a href="" class="terms-link">Privacy Policy</a>
                    </label>
                    @error('terms')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary register-btn">
                Create Account
                <i class="fas fa-user-plus ms-2 icon-right"></i>
            </button>
            
            <div class="login-text text-center">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </form>
    </div>

    

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>