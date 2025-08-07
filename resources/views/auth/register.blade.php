{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Registration</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favi.png') }}">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <style>
        /*
         * Consolidated and improved CSS for the role radios
         */
        .role-radio-group {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            justify-content: center;
        }
        .role-radio {
            position: relative;
            display: flex;
            align-items: center;
            cursor: pointer;
            font-weight: 500;
            user-select: none;
            padding-left: 2rem;
            color: #555;
            transition: color 0.2s;
        }
        .role-radio:hover {
            color: #042414;
        }
        .role-radio input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        .role-radio .custom-radio {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            height: 1.2rem;
            width: 1.2rem;
            background-color: #f1f1f1;
            border-radius: 50%;
            border: 2px solid #0b6414;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .role-radio input[type="radio"]:checked ~ .custom-radio {
            background-color: #042414;
            border-color: #177814;
        }
        .role-radio .custom-radio:after {
            content: "";
            height: 0.5rem;
            width: 0.5rem;
            border-radius: 50%;
            background: #fff;
            opacity: 0;
            transform: scale(0);
            transition: transform 0.2s, opacity 0.2s;
        }
        .role-radio input[type="radio"]:checked ~ .custom-radio:after {
            opacity: 1;
            transform: scale(1);
        }
        /* Style for the password show/hide button */
        .password-field {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/img/logo/logo-v2.png') }}" alt="Company Logo" class="logo">
        </div>
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif
        <div class="register-header text-center">
            <h1>Create Your Account</h1>
            <p>Join us today to access all our services</p>
        </div>
        <form id="registerForm" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="fullName" class="form-label visually-hidden">Full Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="fullName" name="name" placeholder="Full Name" required aria-required="true" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="userEmail" class="form-label visually-hidden">Email address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="userEmail" name="email" placeholder="Email address" required autocomplete="username" aria-required="true" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="userPassword" class="form-label visually-hidden">Password</label>
                <div class="password-field">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="userPassword" name="password" placeholder="Password" required autocomplete="new-password" aria-required="true">
                    <span class="toggle-password" id="togglePassword"><i class="fas fa-eye-slash"></i></span>
                </div>
                <div class="password-strength mt-2"></div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label for="confirmPassword" class="form-label visually-hidden">Confirm Password</label>
                <div class="password-field">
                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password" aria-required="true">
                    <span class="toggle-password" id="toggleConfirmPassword"><i class="fas fa-eye-slash"></i></span>
                </div>
                <div class="password-match mt-2"></div>
            </div>
            @if ($errors->any() && !$errors->hasAny(['name', 'email', 'password', 'terms']))
                <div class="alert alert-danger mt-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <label class="form-label mb-2">Select Role:</label>
            <div class="role-radio-group">
                <label class="role-radio">
                    <input type="radio" id="role_admin" name="role" value="admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                    <span class="custom-radio"></span>
                    Admin
                </label>
                <label class="role-radio">
                    <input type="radio" id="role_accounts" name="role" value="accounts" {{ old('role') == 'accounts' ? 'checked' : '' }}>
                    <span class="custom-radio"></span>
                    Accounts
                </label>
                <label class="role-radio">
                    <input type="radio" id="role_technician" name="role" value="technician" {{ old('role') == 'technician' ? 'checked' : '' }}>
                    <span class="custom-radio"></span>
                    Technician
                </label>
                <label class="role-radio">
                    <input type="radio" id="role_manager" name="role" value="manager" {{ old('role') == 'manager' ? 'checked' : '' }}>
                    <span class="custom-radio"></span>
                    Manager
                </label>
            </div>


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
            <button type="submit" class="btn btn-primary register-btn w-100">
                Create Account
                <i class="fas fa-user-plus ms-2 icon-right"></i>
            </button>
            <div class="login-text text-center mt-3">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#userPassword');
            const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
            const confirmPassword = document.querySelector('#confirmPassword');

            if (togglePassword) {
                togglePassword.addEventListener('click', function (e) {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            if (toggleConfirmPassword) {
                toggleConfirmPassword.addEventListener('click', function (e) {
                    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPassword.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
        });
    </script>
</body>
</html>