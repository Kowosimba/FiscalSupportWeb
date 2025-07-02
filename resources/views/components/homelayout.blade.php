<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Fiscal Support Services</title>
    <meta name="description" content="Fiscal Support Services">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favi.png') }}">

    <link rel="preload" href="{{ asset('assets/css/bootstrap.min.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/css/style.css') }}" as="style">
    <link rel="preload" href="{{ asset('assets/js/vendor/jquery-3.7.1.min.js') }}" as="script">
    <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
 <script src="assets/js/swiper-bundle.min.js"></script>


    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/default.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome-all.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}" media="print"
        onload="this.media='all'">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" media="print"
        onload="this.media='all'">

    @stack('styles')
    @if (session('message'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show immediately without waiting for DOMContentLoaded
        Swal.fire({
            title: 'Success!',
            text: '{{ session('message') }}',
            icon: 'success',
            confirmButtonText: 'OK',
            timer: 3000,
            timerProgressBar: true,
            didOpen: () => {
                // Hide the preloader when SweetAlert opens
                const preloader = document.getElementById('preloader');
                if (preloader) preloader.style.display = 'none';
            },
            willClose: () => {
                // Reset form and close modal after alert closes
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
        });
    </script>
    @endif

    @if (session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Show immediately without waiting for DOMContentLoaded
        Swal.fire({
            title: 'Error!',
            text: '{{ session('error') }}',
            icon: 'error',
            confirmButtonText: 'OK',
            didOpen: () => {
                // Hide the preloader when SweetAlert opens
                const preloader = document.getElementById('preloader');
                if (preloader) preloader.style.display = 'none';
            }
        });
    </script>
    @endif
</head>

<body>
    <div id="preloader" class="white-bg">
        <div id="loader" class="loader">
            <div class="loader-container">
                <div class="loader-icon">
                    <img src="{{ asset('assets/img/logo/preloader.png') }}" alt="Preloader" width="150" height="50"
                        loading="lazy">
                </div>
            </div>
        </div>
    </div>

    <button class="scroll__top scroll-to-target" data-target="html" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

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
                            <img src="{{ asset('assets/img/others/ticket.jpg') }}" alt="Support Ticket" width="400"
                                height="500" loading="lazy">
                        </div>
                    </div>
                    <div class="col-lg-7 ticket-form-col">
                        <p class="form-description">Fill out the form below and our team will get back to you shortly.
                        </p>

                        <form action="{{ route('submit.ticket') }}" method="POST" class="ticket-form"
                            enctype="multipart/form-data" id="supportTicketForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="Email Address" value="{{ old('email') }}" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <select name="service" id="service" class="hidden-select" required>
                                            <option value="" disabled selected>Select Service</option>
                                            <option value="Fiscal Device Setup"
                                                {{ old('service') == 'Fiscal Device Setup' ? 'selected' : '' }}>Fiscal
                                                Device Setup</option>
                                            <option value="Technical Support"
                                                {{ old('service') == 'Technical Support' ? 'selected' : '' }}>Technical
                                                Support</option>
                                            <option value="Billing Inquiry"
                                                {{ old('service') == 'Billing Inquiry' ? 'selected' : '' }}>Billing
                                                Inquiry</option>
                                            <option value="Software Update"
                                                {{ old('service') == 'Software Update' ? 'selected' : '' }}>Software
                                                Update</option>
                                            <option value="Other" {{ old('service') == 'Other' ? 'selected' : '' }}>
                                                Other</option>
                                        </select>

                                        <div class="custom-select" id="customSelect">
                                            <span class="custom-select-trigger" id="customSelectTrigger">Select
                                                Service</span>
                                            <div class="custom-options">
                                                <span class="custom-option" data-value="Fiscal Device Setup">Fiscal Device
                                                    Setup</span>
                                                <span class="custom-option" data-value="Technical Support">Technical
                                                    Support</span>
                                                <span class="custom-option" data-value="Billing Inquiry">Billing
                                                    Inquiry</span>
                                                <span class="custom-option" data-value="Software Update">Software
                                                    Update</span>
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
                                        <input type="text" class="form-control" name="contact_details"
                                            id="contact_details"
                                            placeholder="Contact Details (Phone, WhatsApp, etc.)"
                                            value="{{ old('contact_details') }}">
                                        @error('contact_details')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="text-muted mt-1 d-block">How can we best reach you if needed?</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea name="message" id="message" class="form-control"
                                            placeholder="Describe your issue in detail" rows="4"
                                            required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="attachment" class="form-label d-block mb-2">Attachment
                                            (Optional)</label>
                                        <div class="custom-file-input">
                                            <input type="file" name="attachment" id="attachment"
                                                class="form-control">
                                            <div class="file-input-trigger">
                                                <span class="file-name" id="fileName">No file selected</span>
                                                <span class="file-browse">Browse Files</span>
                                            </div>
                                        </div>
                                        <small class="text-muted mt-1 d-block">Max file size: 5MB (PDF, JPG, PNG)</small>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="submit-btn">Submit Ticket</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button id="openSupportTicket" class="support-ticket-trigger" aria-label="Open support ticket">
        <i class="fas fa-headset"></i> Support
    </button>

    <header>
        <div id="header-fixed-height"></div>
        <div class="tg-header__top tg-header__top-four d-lg-block d-none">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6">
                        <ul
                            class="tg-header__top-info left-side list-wrap justify-content-xl-start justify-content-center">
                            <li>We're ready to provide the best Fiscalisation solution for you!!<a
                                    href="{{ route('pricing') }}">Get Started <i class="fas fa-arrow-right"></i></a>
                            </li>
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

                <!-- Simple Login Link -->
         <li class="header-login" style="margin-left: 20px; display: flex; align-items: center;">

    <!-- Vertical Divider -->
    <span style="border-left: 1px solid var(--tg-heading-color); height: 20px; margin-right: 15px;"></span>

    <!-- Login Link -->
    <a href="{{ route('show.login') }}" class="login-link" style="color: var(--tg-heading-color); font-weight: 500; text-decoration: none; display: flex; align-items: center; gap: 6px;">
        <i class="fas fa-sign-in-alt"></i>
        <span>Login</span>
    </a>

</li>

            </ul>
        </li>
    </ul>
</div>

                </div>
            </div>
        </div>
        <div id="sticky-header" class="tg-header__area tg-header__area-five">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="tgmenu__wrap">
                            <nav class="tgmenu__nav">
                                <div class="logo">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ asset('assets/img/logo/logo-v2.png') }}"
                                             alt="Logo" 
                                             width="180" 
                                             height="85" 
                                             loading="lazy">
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
        <div class="tgmobile__menu">
            <nav class="tgmobile__menu-box">
                <div class="close-btn"><i class="fas fa-times"></i></div>
                <div class="nav-logo">
                    
                </div>
                <div class="tgmobile__menu-outer"></div>
                <div class="tgmobile__menu-bottom">
                    <div class="contact-info">
                        <ul class="list-wrap">
                            <li>Mail: <a href="mailto:sales2@fiscalsupportservices.com">sales@fiscalsupportservices.com</a>
                            </li>
                            <li>Phone: <a href="tel:+263292270666">+263292270666</a></li>
                        </ul>
                    </div>
                    <div class="social-links">
                        <ul class="list-wrap">
                            <li><a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"
                                    aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                            <li><a href="https://twitter.com/" target="_blank" rel="noopener noreferrer"
                                    aria-label="Twitter">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.33192 5.92804L13.5438 0H12.3087L7.78328 5.14724L4.16883 0H0L5.46575 7.78353L0 14H1.2351L6.01407 8.56431L9.83119 14H14L8.33192 5.92804ZM6.64027 7.85211L6.08648 7.07704L1.68013 0.909771H3.57718L7.13316 5.88696L7.68694 6.66202L12.3093 13.1316H10.4123L6.64027 7.85211Z"
                                            fill="currentColor" />
                                    </svg>
                                </a></li>
                            <li><a href="https://www.whatsapp.com/" target="_blank" rel="noopener noreferrer"
                                    aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a></li>
                            <li><a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"
                                    aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="https://www.youtube.com/" target="_blank"
                                    rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main class="fix">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('home-content')
    </main>

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
                                        Fiscal Support Services (Private) Limited is dedicated to empowering businesses
                                        through cost-effective and efficient technology solutions.     
                                    </p>
                                    <div class="social-links style3">
                                        <ul class="list-wrap">
                                            <li><a href="https://www.facebook.com/" target="_blank"
                                                    rel="noopener noreferrer" aria-label="Facebook"><i
                                                        class="fab fa-facebook-f"></i></a></li>
                                            <li><a href="https://twitter.com/" target="_blank"
                                                    rel="noopener noreferrer" aria-label="Twitter">
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M8.33192 5.92804L13.5438 0H12.3087L7.78328 5.14724L4.16883 0H0L5.46575 7.78353L0 14H1.2351L6.01407 8.56431L9.83119 14H14L8.33192 5.92804ZM6.64027 7.85211L6.08648 7.07704L1.68013 0.909771H3.57718L7.13316 5.88696L7.68694 6.66202L12.3093 13.1316H10.4123L6.64027 7.85211Z"
                                                            fill="currentColor" />
                                                    </svg>
                                                </a></li>
                                            <li><a href="https://www.linkedin.com/" target="_blank"
                                                    rel="noopener noreferrer" aria-label="LinkedIn"><i
                                                        class="fab fa-linkedin-in"></i></a></li>
                                            <li><a href="https://www.instagram.com/" target="_blank"
                                                    rel="noopener noreferrer" aria-label="Instagram"><i
                                                        class="fab fa-instagram"></i></a></li>
                                            <li><a href="https://www.skype.com/" target="_blank"
                                                    rel="noopener noreferrer" aria-label="Skype"><i
                                                        class="fab fa-skype"></i></a></li>
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
                                        <li><a href="{{ route('blog.index') }} }}">News & Updates</a></li>
                                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-auto col-md-6">
                                <div class="footer__widget footer__links">
                                    <h3 class="footer__widget-title">Services</h3>
                                    <ul class="list-wrap">
                                        <li><a href="{{ route('services.index') }}">Virtual Fiscalisation</a></li>
                                        <li><a href="{{ route('services.index') }}">Fiscal Device Support/a></li>
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
                                        Contact us for strategic technology consulting and support tailored to your
                                        business needs.
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Cache DOM elements
            const modal = document.getElementById('supportTicketModal');
            const body = document.body;

            // Event delegation for better performance
            document.addEventListener('click', function(e) {
                // Handle modal opening
                if (e.target.id === 'openSupportTicket' || e.target.closest('#openSupportTicket')) {
                    modal.style.display = 'block';
                    body.style.overflow = 'hidden';
                }

                // Handle modal closing
                if (e.target.id === 'closeTicketModal' ||
                    e.target.id === 'modalOverlay' ||
                    (e.target.classList.contains('close-btn') && e.target.closest('.modal-header'))) {
                    modal.style.display = 'none';
                    body.style.overflow = '';
                }

                // Custom select functionality
                if (e.target.id === 'customSelectTrigger' || e.target.closest('#customSelectTrigger')) {
                    document.getElementById('customSelect').classList.toggle('opened');
                }

                // Handle option selection
                if (e.target.classList.contains('custom-option')) {
                    const value = e.target.getAttribute('data-value');
                    const hiddenSelect = document.getElementById('service');
                    const customSelectTrigger = document.getElementById('customSelectTrigger');
                    const customOptions = document.querySelectorAll('.custom-option');

                    // Update hidden select
                    for (let i = 0; i < hiddenSelect.options.length; i++) {
                        if (hiddenSelect.options[i].value === value) {
                            hiddenSelect.selectedIndex = i;
                            break;
                        }
                    }

                    // Update custom select visual
                    customSelectTrigger.textContent = e.target.textContent;

                    // Remove previous selection
                    customOptions.forEach(opt => opt.classList.remove('selection'));

                    // Add selection class
                    e.target.classList.add('selection');

                    // Close dropdown
                    document.getElementById('customSelect').classList.remove('opened');
                }
            });

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    modal.style.display = 'none';
                    body.style.overflow = '';
                }
            });

            // File input handling
            const fileInput = document.getElementById('attachment');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const fileName = document.getElementById('fileName');
                    if (this.files.length > 0) {
                        fileName.textContent = this.files[0].name;
                    } else {
                        fileName.textContent = 'No file selected';
                    }
                });
            }

            // Initialize selected service if exists
            const hiddenSelect = document.getElementById('service');
            if (hiddenSelect && hiddenSelect.value) {
                const selectedOption = document.querySelector(`.custom-option[data-value="${hiddenSelect.value}"]`);
                if (selectedOption) {
                    const customSelectTrigger = document.getElementById('customSelectTrigger');
                    if (customSelectTrigger) {
                        customSelectTrigger.textContent = selectedOption.textContent;
                        selectedOption.classList.add('selection');
                    }
                }
            }

            // Helper functions
            function resetForm() {
                const form = document.getElementById('supportTicketForm');
                if (form) form.reset();
                const fileName = document.getElementById('fileName');
                if (fileName) fileName.textContent = 'No file selected';
                const customSelectTrigger = document.getElementById('customSelectTrigger');
                if (customSelectTrigger) customSelectTrigger.textContent = 'Select Service';
            }

            function closeModal() {
                const modal = document.getElementById('supportTicketModal');
                if (modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            }
        });
    </script>
<script>
    // Wait for document to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Check if SweetAlert2 is loaded and if there's a session message
        if (typeof Swal !== 'undefined') {
            @if(session('message'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('message') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    // Reset form and close modal after alert closes
                    const form = document.getElementById('supportTicketForm');
                    if (form) form.reset();
                    
                    const fileName = document.getElementById('fileName');
                    if (fileName) fileName.textContent = 'No file selected';
                    
                    const customSelectTrigger = document.getElementById('customSelectTrigger');
                    if (customSelectTrigger) customSelectTrigger.textContent = 'Select Service';
                    
                    const modal = document.getElementById('supportTicketModal');
                    if (modal) modal.style.display = 'none';
                    
                    document.body.style.overflow = '';
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif
        } else {
            console.error('SweetAlert2 is not loaded!');
        }
    });
</script>
    @stack('scripts')
</body>

</html>