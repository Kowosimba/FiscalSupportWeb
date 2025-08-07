<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Fiscal Support Services</title>
    <meta name="description" content="Comprehensive fiscal device support and IT solutions">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favi.png') }}">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('assets/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/css/style.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}" as="script">

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}">

    <!-- Deferred CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/default.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome-all.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" media="print" onload="this.media='all'">

    @stack('styles')
    

        <style>
        /* Enhanced Mobile Navigation Styles */
        .tgmobile__menu {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
        }
        
        .tgmobile__menu.active {
            opacity: 1;
            visibility: visible;
        }
        
        .tgmobile__menu-box {
            position: fixed;
            top: 0;
            left: -100%;
            width: 300px;
            height: 100%;
            background: #fff;
            z-index: 1;
            padding: 30px;
            transition: all 0.5s ease;
            overflow-y: auto;
        }
        
        .tgmobile__menu.active .tgmobile__menu-box {
            left: 0;
        }
        
        .close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .close-btn:hover {
            color: #0f8447;
            transform: rotate(90deg);
        }
        
        .tgmobile__menu-outer {
            margin-top: 40px;
        }
        
        .tgmobile__menu-outer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .tgmobile__menu-outer ul li {
            position: relative;
            margin-bottom: 15px;
        }
        
        .tgmobile__menu-outer ul li a {
            display: block;
            font-size: 16px;
            font-weight: 500;
            color: #333;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            transition: all 0.3s ease;
        }
        
        .tgmobile__menu-outer ul li a:hover {
            color: #0f8447;
            padding-left: 10px;
        }
        
        .tgmobile__menu-bottom {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .contact-info ul {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
        }
        
        .contact-info ul li {
            margin-bottom: 10px;
            font-size: 14px;
            color: #666;
        }
        
        .contact-info ul li a {
            color: #0f8447;
            transition: all 0.3s ease;
        }
        
        .contact-info ul li a:hover {
            color: #06621c;
        }
        
        .social-links ul {
            display: flex;
            gap: 15px;
            padding: 0;
            margin: 0;
        }
        
        .social-links ul li a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: #f5f5f5;
            border-radius: 50%;
            color: #333;
            transition: all 0.3s ease;
        }
        
        .social-links ul li a:hover {
            background: #0f8447;
            color: #fff;
            transform: translateY(-3px);
        }
        
        /* Mobile menu toggler button */
        .mobile-nav-toggler {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            z-index: 999;
        }
        
        .sidebar-btn {
            width: 40px;
            height: 40px;
            background: #0f8447;
            border: none;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .sidebar-btn:hover {
            background: #06621c;
        }
        
        .sidebar-btn .line {
            display: block;
            width: 22px;
            height: 2px;
            background: #fff;
            margin: 4px 0;
            transition: all 0.3s ease;
        }
        
        .sidebar-btn.active .line:nth-child(1) {
            transform: translateY(6px) rotate(45deg);
        }
        
        .sidebar-btn.active .line:nth-child(2) {
            opacity: 0;
        }
        
        .sidebar-btn.active .line:nth-child(3) {
            transform: translateY(-6px) rotate(-45deg);
        }
        
        /* Mobile header adjustments */
        @media (max-width: 991px) {
            .tgmenu__wrap {
                padding: 15px 0;
                position: relative;
            }
            
            .logo img {
                max-height: 50px;
            }
            
            .mobile-nav-toggler {
                display: inline-flex !important;
            }
        }
        
        /* Mobile menu animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .tgmobile__menu-outer ul li {
            opacity: 0;
            animation: fadeInUp 0.4s ease forwards;
        }
        
        .tgmobile__menu-outer ul li:nth-child(1) { animation-delay: 0.1s; }
        .tgmobile__menu-outer ul li:nth-child(2) { animation-delay: 0.2s; }
        .tgmobile__menu-outer ul li:nth-child(3) { animation-delay: 0.3s; }
        .tgmobile__menu-outer ul li:nth-child(4) { animation-delay: 0.4s; }
        .tgmobile__menu-outer ul li:nth-child(5) { animation-delay: 0.5s; }
        .tgmobile__menu-outer ul li:nth-child(6) { animation-delay: 0.6s; }
        .tgmobile__menu-outer ul li:nth-child(7) { animation-delay: 0.7s; }
        .tgmobile__menu-outer ul li:nth-child(8) { animation-delay: 0.8s; }
        
        /* Login button for mobile */
        .mobile-login-btn {
            display: none;
            background: #0f8447;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: 500;
            margin-left: auto;
            margin-right: 60px;
            transition: all 0.3s ease;
        }
        
        .mobile-login-btn:hover {
            background: #06621c;
            color: white;
        }
        
        @media (max-width: 991px) {
            .mobile-login-btn {
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }
            
            .header-login {
                display: none !important;
            }
        }
    </style>
    <!-- Enhanced Create Ticket Button Styles -->
    <style>
        /* Enhanced Support Ticket Button */
        .support-ticket-trigger {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: linear-gradient(135deg, #16582f), #0f8447);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(10, 73, 24, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            user-select: none;
            backdrop-filter: blur(10px);
            min-width: auto;
        }

        .support-ticket-trigger:hover {
            background: linear-gradient(135deg, #45a216, #16582f);
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(6, 173, 224, 0.4);
            color: white;
        }

        .support-ticket-trigger:active {
            transform: translateY(0);
        }

        .support-ticket-trigger i {
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .support-ticket-trigger:hover i {
            transform: rotate(15deg) scale(1.1);
        }

        /* Make button draggable */
        .support-ticket-trigger.dragging {
            opacity: 0.8;
            cursor: grabbing;
        }

        /* Responsive adjustments for button */
        @media (max-width: 768px) {
            .support-ticket-trigger {
                bottom: 20px;
                right: 20px;
                padding: 10px 16px;
                font-size: 13px;
            }
            
            .support-ticket-trigger i {
                font-size: 14px;
            }
        }

        /* Ensure button stays above other elements */
        .support-ticket-trigger {
            z-index: 9999;
        }

        /* Compact mobile version */
@media (max-width: 768px) {
    .support-ticket-trigger span {
        display: none; /* Hide text on mobile */
    }
    
    .support-ticket-trigger {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        padding: 0;
        justify-content: center;
        bottom: 20px !important;
        right: 20px !important;
        z-index: 99999 !important;
        display: flex !important;
        visibility: visible !important;
    }
    
    .support-ticket-trigger i {
        font-size: 18px !important;
        margin: 0;
    }
}

    </style>
    
    <!-- Session message handling -->
    @if (session('message'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Success!',
                text: '{{ session('message') }}',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 5000,
                timerProgressBar: true,
                didOpen: () => {
                    const preloader = document.getElementById('preloader');
                    if (preloader) preloader.style.display = 'none';
                },
                willClose: () => {
                    resetTicketForm();
                }
            });
        });

        function resetTicketForm() {
            const form = document.getElementById('supportTicketForm');
            if (form) form.reset();
            
            const fileName = document.getElementById('fileName');
            if (fileName) fileName.textContent = 'No file selected';
            
            const customSelectTrigger = document.getElementById('customSelectTrigger');
            if (customSelectTrigger) customSelectTrigger.textContent = 'Select Service';
            
            const modal = document.getElementById('supportTicketModal');
            if (modal) modal.style.display = 'none';
            
            document.body.style.overflow = '';
        }
    </script>
    @endif

    @if (session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK',
                didOpen: () => {
                    const preloader = document.getElementById('preloader');
                    if (preloader) preloader.style.display = 'none';
                }
            });
        });
    </script>
    @endif
</head>

<body>
    <!-- Preloader -->
    <div id="preloader" class="white-bg">
        <div id="loader" class="loader">
            <div class="loader-container">
                <div class="loader-icon">
                    <img src="{{ asset('assets/img/logo/preloader.png') }}" alt="Preloader" width="150" height="50" loading="lazy">
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to top button -->
    <button class="scroll__top scroll-to-target" data-target="html" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Enhanced Create Ticket Button with Paper Airplane Icon -->
    <button id="openSupportTicket" class="support-ticket-trigger" aria-label="Create support ticket">
        <i class="fas fa-paper-plane"></i>
        <span>Create Ticket</span>
    </button>

    <!-- Support Ticket Modal -->
    <div class="support-ticket-modal" id="supportTicketModal">
        <div class="modal-overlay" id="modalOverlay"></div>
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Create Support Ticket</h2>
                <button type="button" class="close-btn" id="closeTicketModal" aria-label="Close">&times;</button>
            </div>

            <div class="modal-body">
                <div class="row g-0">
                    <div class="col-lg-5 ticket-image-col">
                        <div class="ticket-image">
                            <img src="{{ asset('assets/img/others/ticket.jpg') }}" alt="Support Ticket" width="400" height="500" loading="lazy">
                        </div>
                    </div>
                    <div class="col-lg-7 ticket-form-col">
                        <p class="form-description">Fill out the form below and our team will get back to you within 24 hours.</p>

                        <form action="{{ route('submit.ticket') }}" method="POST" class="ticket-form" enctype="multipart/form-data" id="supportTicketForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Company Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="service" id="service" class="hidden-select" required>
                                            <option value="" disabled selected>Select Service</option>
                                            <option value="Fiscal Device Setup" {{ old('service') == 'Fiscal Device Setup' ? 'selected' : '' }}>Fiscal Device Setup</option>
                                            <option value="Technical Support" {{ old('service') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                                            <option value="Billing Inquiry" {{ old('service') == 'Billing Inquiry' ? 'selected' : '' }}>Billing Inquiry</option>
                                            <option value="Software Update" {{ old('service') == 'Software Update' ? 'selected' : '' }}>Software Update</option>
                                            <option value="Other" {{ old('service') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>

                                        <div class="custom-select" id="customSelect">
                                            <span class="custom-select-trigger" id="customSelectTrigger">Select Service</span>
                                            <div class="custom-options">
                                                <span class="custom-option" data-value="Fiscal Device Setup">Fiscal Device Setup</span>
                                                <span class="custom-option" data-value="Technical Support">Technical Support</span>
                                                <span class="custom-option" data-value="Billing Inquiry">Billing Inquiry</span>
                                                
                                                <span class="custom-option" data-value="Other">Other</span>
                                            </div>
                                        </div>
                                        @error('service')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="contact_details" id="contact_details" 
                                            placeholder="Contact Details (Phone, WhatsApp, etc.)" value="{{ old('contact_details') }}" required>
                                        @error('contact_details')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">How can we best reach you if needed?</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea name="message" id="message" class="form-control" placeholder="Describe your issue in detail" rows="4" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="attachment" class="form-label d-block mb-2">Attachment (Optional)</label>
                                        <div class="custom-file-input">
                                            <input type="file" name="attachment" id="attachment" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                            <div class="file-input-trigger">
                                                <span class="file-name" id="fileName">No file selected</span>
                                                <span class="file-browse">Browse Files</span>
                                            </div>
                                        </div>
                                        <small class="text-muted mt-1 d-block">Max file size: 5MB (PDF, JPG, PNG)</small>
                                        @error('attachment')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="submit-btn" id="submitTicketBtn">
                                        <span class="btn-text">Submit Ticket</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Section -->
    <header>
        <div id="header-fixed-height"></div>
        <div class="tg-header__top tg-header__top-four d-lg-block d-none">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <ul class="tg-header__top-info left-side list-wrap justify-content-xl-start justify-content-center">
                            <li>We're ready to provide the best Fiscalisation solution for you!!<a href="{{ route('pricing') }}">Get Started <i class="fas fa-arrow-right"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-xl-6 col-md-4 d-xl-block d-none">
                        <ul class="tg-header__top-right list-wrap">
                            <li class="tg-header__top-social">
                                <ul class="list-wrap d-flex align-items-center">
                                    <li>Follow Us On: </li>
                                    <li>
                                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8.33192 5.92804L13.5438 0H12.3087L7.78328 5.14724L4.16883 0H0L5.46575 7.78353L0 14H1.2351L6.01407 8.56431L9.83119 14H14L8.33192 5.92804ZM6.64027 7.85211L6.08648 7.07704L1.68013 0.909771H3.57718L7.13316 5.88696L7.68694 6.66202L12.3093 13.1316H10.4123L6.64027 7.85211Z" fill="var(--tg-heading-color)" />
                                            </svg>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    </li>

                                    <!-- Login Link with exact spacing -->
                                    <li class="header-login" style="margin-left: 20px; display: flex; align-items: center;">
                                         <span style="border-left: 1px solid var(--tg-heading-color); height: 20px; margin-right: 15px;"></span>
                                
                                @auth
                                    <!-- Show Dashboard link for authenticated users -->
                                    <a href="{{ route('admin.index') }}" class="login-link" style="color: var(--tg-heading-color); font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span>Dashboard</span>
                                    </a>
                                @else
                                    <!-- Show Login link for guests -->
                                    <a href="{{ route('show.login') }}" class="login-link" style="color: var(--tg-heading-color); font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Login</span>
                                    </a>
                                @endauth
                            </li>

                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Navigation with exact spacing -->
        <div id="sticky-header" class="tg-header__area tg-header__area-five">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="tgmenu__wrap">
                            <nav class="tgmenu__nav">
                                <div class="logo">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ asset('assets/img/logo/logo-v2.png') }}" alt="Logo" width="180" height="85" loading="lazy">
                                    </a>
                                </div>
                                <div class="tgmenu__navbar-wrap tgmenu__main-menu d-none d-lg-flex">
                                    <ul class="navigation">
                                        <li><a href="{{ route('home') }}">Home</a></li>
                                        <li><a href="{{ route('about') }}">About Us</a></li>
                                        <li><a href="{{ Route('services.index') }}">Services</a></li>
                                        <li><a href="{{ route('pricing') }}">Pricing</a></li>
                                        <li><a href="{{ route('team') }}">Our Team</a></li>
                                        <li><a href="{{ route('faqs') }}">FAQs</a></li>
                                        <li><a href="{{ route('blog.index') }}">News & Updates</a></li>
                                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                    </ul>
                                </div>
                                <div class="mobile-nav-toggler d-lg-none d-inline-flex">
                                    <button class="sidebar-btn" aria-label="Menu">
                                        <span class="line"></span>
                                        <span class="line"></span>
                                        <span class="line"></span>
                                    </button>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Enhanced Mobile Navigation -->
    <div class="tgmobile__menu">
        <div class="tgmobile__menu-box">
            <div class="close-btn"><i class="fas fa-times"></i></div>
            <div class="tgmobile__menu-outer">
            </div>
             <!-- Mobile Login Button -->
                <div class="mobile-login-container" style="margin-bottom: 20px;">
                    @auth
                        <a href="{{ route('admin.index') }}" class="mobile-login-btn" style="display: block; text-align: center;">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('show.login') }}" class="mobile-login-btn" style="display: block; text-align: center;">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    @endauth
                </div>
            
            <div class="tgmobile__menu-bottom">
                
                <div class="contact-info">
                    <ul class="list-wrap">
                        <li><i class="fas fa-envelope"></i> <a href="mailto:sales@fiscalsupportservices.com">sales@fiscalsupportservices.com</a></li>
                        <li><i class="fas fa-phone"></i> <a href="tel:+263292270666">+263 292 270666</a></li>
                        <li><i class="fas fa-map-marker-alt"></i> 36 East Road, Belgravia, Harare</li>
                    </ul>
                </div>
                <div class="social-links">
                    
                    <ul class="list-wrap">
                        <li><a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
                        <li><a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="https://wa.me/263780526944" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    </header>

    <!-- Main Content -->
    <main class="fix">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('home-content')
    </main>

    <!-- Footer Section -->
    <footer>
        <div class="footer__area footer__area-five">
            <div class="footer-wrap5 gray-bg">
                <div class="container">
                    <div class="footer__middle d-none d-md-block">
                        <div class="row justify-content-between">
                            <div class="col-xl-3 col-lg-4 col-md-6">
                                <div class="footer__widget footer__about">
                                    <h3 class="footer__widget-title">About Company</h3>
                                    <p class="footer__content mb-35">
                                        Fiscal Support Services (Private) Limited is dedicated to empowering businesses through cost-effective and efficient technology solutions.     
                                    </p>
                                    <div class="social-links style3">
                                        <ul class="list-wrap">
                                            <li><a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.33192 5.92804L13.5438 0H12.3087L7.78328 5.14724L4.16883 0H0L5.46575 7.78353L0 14H1.2351L6.01407 8.56431L9.83119 14H14L8.33192 5.92804ZM6.64027 7.85211L6.08648 7.07704L1.68013 0.909771H3.57718L7.13316 5.88696L7.68694 6.66202L12.3093 13.1316H10.4123L6.64027 7.85211Z" fill="currentColor" />
                                                </svg>
                                            </a></li>
                                            <li><a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
                                            <li><a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                                            <li><a href="https://www.skype.com/" target="_blank" rel="noopener noreferrer" aria-label="Skype"><i class="fab fa-skype"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-auto col-md-6">
                                <div class="footer__widget footer__links">
                                    <h3 class="footer__widget-title">Quick Links</h3>
                                    <ul class="list-wrap">
                                        <li><a href="{{ route('about') }}">About Us</a></li>
                                        <li><a href="{{ route('pricing') }}">Pricing</a></li>
                                        <li><a href="{{ route('services.index') }}">Our Team</a></li>
                                        <li><a href="{{ route('blog.index') }}">News & Updates</a></li>
                                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-auto col-md-6">
                                <div class="footer__widget footer__links">
                                    <h3 class="footer__widget-title">Services</h3>
                                    <ul class="list-wrap">
                                        <li><a href="{{ route('services.index') }}">Virtual Fiscalisation</a></li>
                                        <li><a href="{{ route('services.index') }}">Fiscal Device Support</a></li>
                                        <li><a href="{{ route('services.index') }}">Pastel Accounting Software</a></li>
                                        <li><a href="{{ route('services.index') }}">Infrastructure Planning</a></li>
                                        <li><a href="{{ route('services.index') }}">IT Consulting</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6">
                                <div class="footer__widget footer__newsletter">
                                    <h3 class="footer__widget-title">Get In Touch</h3>
                                    <p class="footer__content mb-35">
                                        Contact us for strategic technology consulting and support tailored to your business needs.
                                    </p>
                                    <form class="footer__newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                                        @csrf
                                        <div class="form-icon">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <input id="email" name="email" type="email" placeholder="Enter Your Email" required>
                                        <button class="btn btn-five" type="submit">Subscribe</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer__bottom text-center">
                        <div class="container">
                            <div class="footer__copyright">
                                © {{ date('Y') }} Fiscal Support Services. All Rights Reserved.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/main.js') }}" defer></script>
    <script src="{{ asset('assets/js/magnific-popup.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/ajax-form.js') }}" defer></script>
    <script src="{{ asset('assets/js/wow.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/aos.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.datetimepicker.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/gsap.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/ScrollTrigger.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/SplitText.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/jquery.counterup.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/imagesloaded.pkgd.min.js') }}" defer></script>
    <script src="{{ asset('assets/js/isotope.pkgd.min.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    <!-- Enhanced JavaScript with Draggable Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cache DOM elements
            const modal = document.getElementById('supportTicketModal');
            const overlay = document.getElementById('modalOverlay');
            const openBtn = document.getElementById('openSupportTicket');
            const closeBtn = document.getElementById('closeTicketModal');
            const body = document.body;
            const form = document.getElementById('supportTicketForm');
            const submitBtn = document.getElementById('submitTicketBtn');
            const fileInput = document.getElementById('attachment');
            const fileName = document.getElementById('fileName');
            const customSelect = document.getElementById('customSelect');
            const customSelectTrigger = document.getElementById('customSelectTrigger');
            const hiddenSelect = document.getElementById('service');

            // Draggable functionality for support button
            let isDragging = false;
            let startX, startY, initialX, initialY;
            let hasMoved = false;

            openBtn.addEventListener('mousedown', function(e) {
                isDragging = true;
                hasMoved = false;
                startX = e.clientX;
                startY = e.clientY;
                
                const rect = openBtn.getBoundingClientRect();
                initialX = rect.left;
                initialY = rect.top;
                
                openBtn.classList.add('dragging');
                e.preventDefault();
            });

            document.addEventListener('mousemove', function(e) {
                if (isDragging) {
                    hasMoved = true;
                    const deltaX = e.clientX - startX;
                    const deltaY = e.clientY - startY;
                    
                    const newX = initialX + deltaX;
                    const newY = initialY + deltaY;
                    
                    // Constrain to viewport
                    const maxX = window.innerWidth - openBtn.offsetWidth;
                    const maxY = window.innerHeight - openBtn.offsetHeight;
                    
                    const constrainedX = Math.max(0, Math.min(newX, maxX));
                    const constrainedY = Math.max(0, Math.min(newY, maxY));
                    
                    openBtn.style.left = constrainedX + 'px';
                    openBtn.style.top = constrainedY + 'px';
                    openBtn.style.right = 'auto';
                    openBtn.style.bottom = 'auto';
                }
            });

            document.addEventListener('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    openBtn.classList.remove('dragging');
                    
                    // If button wasn't moved significantly, treat as click
                    setTimeout(() => {
                        if (!hasMoved) {
                            openModal();
                        }
                        hasMoved = false;
                    }, 10);
                }
            });

            // Touch events for mobile
            openBtn.addEventListener('touchstart', function(e) {
                isDragging = true;
                hasMoved = false;
                const touch = e.touches[0];
                startX = touch.clientX;
                startY = touch.clientY;
                
                const rect = openBtn.getBoundingClientRect();
                initialX = rect.left;
                initialY = rect.top;
                
                openBtn.classList.add('dragging');
                e.preventDefault();
            });

            document.addEventListener('touchmove', function(e) {
                if (isDragging) {
                    hasMoved = true;
                    const touch = e.touches[0];
                    const deltaX = touch.clientX - startX;
                    const deltaY = touch.clientY - startY;
                    
                    const newX = initialX + deltaX;
                    const newY = initialY + deltaY;
                    
                    const maxX = window.innerWidth - openBtn.offsetWidth;
                    const maxY = window.innerHeight - openBtn.offsetHeight;
                    
                    const constrainedX = Math.max(0, Math.min(newX, maxX));
                    const constrainedY = Math.max(0, Math.min(newY, maxY));
                    
                    openBtn.style.left = constrainedX + 'px';
                    openBtn.style.top = constrainedY + 'px';
                    openBtn.style.right = 'auto';
                    openBtn.style.bottom = 'auto';
                    
                    e.preventDefault();
                }
            });

            document.addEventListener('touchend', function() {
                if (isDragging) {
                    isDragging = false;
                    openBtn.classList.remove('dragging');
                    
                    setTimeout(() => {
                        if (!hasMoved) {
                            openModal();
                        }
                        hasMoved = false;
                    }, 10);
                }
            });

            // Modal functionality
            function openModal() {
                modal.style.display = 'block';
                body.style.overflow = 'hidden';
                document.dispatchEvent(new Event('modalOpened'));
            }

            function closeModal() {
                modal.style.display = 'none';
                body.style.overflow = '';
                document.dispatchEvent(new Event('modalClosed'));
            }

            // Event listeners
            closeBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    closeModal();
                }
            });

            // Custom select functionality
            if (customSelectTrigger) {
                customSelectTrigger.addEventListener('click', function() {
                    customSelect.classList.toggle('opened');
                });
            }

            // Handle option selection
            document.querySelectorAll('.custom-option').forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    
                    // Update hidden select
                    Array.from(hiddenSelect.options).forEach(opt => {
                        if (opt.value === value) {
                            hiddenSelect.selectedIndex = opt.index;
                        }
                    });

                    // Update UI
                    customSelectTrigger.textContent = this.textContent;
                    document.querySelectorAll('.custom-option').forEach(opt => {
                        opt.classList.remove('selection');
                    });
                    this.classList.add('selection');
                    customSelect.classList.remove('opened');
                });
            });

            // File input handling
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        fileName.textContent = this.files[0].name;
                        
                        // Validate file size
                        const fileSize = this.files[0].size / 1024 / 1024; // in MB
                        if (fileSize > 5) {
                            Swal.fire({
                                title: 'File Too Large',
                                text: 'Maximum file size is 5MB. Please choose a smaller file.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            this.value = '';
                            fileName.textContent = 'No file selected';
                        }
                    } else {
                        fileName.textContent = 'No file selected';
                    }
                });
            }

            // Form submission handling
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Show loading state
                    submitBtn.disabled = true;
                    submitBtn.querySelector('.btn-text').textContent = 'Submitting...';
                    submitBtn.querySelector('.spinner-border').classList.remove('d-none');
                });
            }

            // Initialize selected service if exists
            if (hiddenSelect && hiddenSelect.value) {
                const selectedOption = document.querySelector(`.custom-option[data-value="${hiddenSelect.value}"]`);
                if (selectedOption) {
                    customSelectTrigger.textContent = selectedOption.textContent;
                    selectedOption.classList.add('selection');
                }
            }

            // Reset form function
            window.resetTicketForm = function() {
                if (form) form.reset();
                if (fileName) fileName.textContent = 'No file selected';
                if (customSelectTrigger) customSelectTrigger.textContent = 'Select Service';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.querySelector('.btn-text').textContent = 'Submit Ticket';
                    submitBtn.querySelector('.spinner-border').classList.add('d-none');
                }
            };
        });
    </script>

     <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenu = document.querySelector('.tgmobile__menu');
            const sidebarBtn = document.querySelector('.sidebar-btn');
            const closeBtn = document.querySelector('.close-btn');
            
            // Show mobile menu
            sidebarBtn.addEventListener('click', function() {
                this.classList.add('active');
                mobileMenu.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            
            // Hide mobile menu
            closeBtn.addEventListener('click', function() {
                sidebarBtn.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
            
            // Close menu when clicking on a link
            document.querySelectorAll('.tgmobile__menu-outer a').forEach(link => {
                link.addEventListener('click', function() {
                    sidebarBtn.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                });
            });
            
            // Close menu when clicking outside
            mobileMenu.addEventListener('click', function(e) {
                if (e.target === mobileMenu) {
                    sidebarBtn.classList.remove('active');
                    mobileMenu.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
            
            // Mobile login button (visible only on mobile)
            const mobileLoginBtn = document.createElement('button');
            mobileLoginBtn.className = 'mobile-login-btn';
            
            @auth
                mobileLoginBtn.innerHTML = '<i class="fas fa-tachometer-alt"></i> Dashboard';
                mobileLoginBtn.addEventListener('click', function() {
                    window.location.href = "{{ route('admin.index') }}";
                });
            @else
                mobileLoginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
                mobileLoginBtn.addEventListener('click', function() {
                    window.location.href = "{{ route('show.login') }}";
                });
            @endauth
            
            // Insert mobile login button in header
            const headerWrap = document.querySelector('.tgmenu__wrap');
            if (headerWrap) {
                headerWrap.insertBefore(mobileLoginBtn, document.querySelector('.mobile-nav-toggler'));
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
