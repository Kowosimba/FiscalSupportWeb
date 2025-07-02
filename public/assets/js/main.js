(function ($) {
    "use strict";

    /*=============================================
        =    		 Preloader			      =
    =============================================*/
    function preloader() {
        $('#preloader').delay(0).fadeOut();
    };

    $(window).on('load', function () {
        preloader();
        wowAnimation();
        aosAnimation();
    });



    /*===========================================
        =    		Mobile Menu			      =
    =============================================*/
    //SubMenu Dropdown Toggle
    if ($('.tgmenu__wrap li.menu-item-has-children ul').length) {
        $('.tgmenu__wrap .navigation li.menu-item-has-children').append('<div class="dropdown-btn"><span class="plus-line"></span></div>');
    }

    //Mobile Nav Hide Show
    if ($('.tgmobile__menu').length) {

        var mobileMenuContent = $('.tgmenu__wrap .tgmenu__main-menu').html();
        $('.tgmobile__menu .tgmobile__menu-box .tgmobile__menu-outer').append(mobileMenuContent);

        //Dropdown Button
        $('.tgmobile__menu li.menu-item-has-children .dropdown-btn').on('click', function () {
            $(this).toggleClass('open');
            $(this).prev('ul').slideToggle(300);
        });
        //Menu Toggle Btn
        $('.mobile-nav-toggler').on('click', function () {
            $('body').addClass('mobile-menu-visible');
        });

        //Menu Toggle Btn
        $('.tgmobile__menu-backdrop, .tgmobile__menu .close-btn').on('click', function () {
            $('body').removeClass('mobile-menu-visible');
        });
    };


    /*=============================================
        =           Data Background             =
    =============================================*/
    $("[data-background]").each(function () {
        $(this).css("background-image", "url(" + $(this).attr("data-background") + ")")
    })
    $("[data-bg-color]").each(function () {
        $(this).css("background-color", $(this).attr("data-bg-color"));
    });

    $("[data-text-color]").each(function () {
        $(this).css("color", $(this).attr("data-text-color"));
    });

    /*=============================================
        =           Data Mask Src             =
    =============================================*/
    if ($('[data-mask-src]').length > 0) {
        $('[data-mask-src]').each(function () {
            var mask = $(this).attr('data-mask-src');
            $(this).css({
                'mask-image': 'url(' + mask + ')',
                '-webkit-mask-image': 'url(' + mask + ')'
            });
            $(this).addClass('bg-mask');
            $(this).removeAttr('data-mask-src');
        });
    };

    /*===========================================
        =     Menu sticky & Scroll to top      =
    =============================================*/
    $(window).on('scroll', function () {
        var scroll = $(window).scrollTop();
        if (scroll < 245) {
            $("#sticky-header").removeClass("sticky-menu");
            $('.scroll-to-target').removeClass('open');
            $("#header-fixed-height").removeClass("active-height");

        } else {
            $("#sticky-header").addClass("sticky-menu");
            $('.scroll-to-target').addClass('open');
            $("#header-fixed-height").addClass("active-height");
        }
    });


    /*=============================================
        =    		 Scroll Up  	         =
    =============================================*/
    if ($('.scroll-to-target').length) {
        $(".scroll-to-target").on('click', function () {
            var target = $(this).attr('data-target');
            // animate
            $('html, body').animate({
                scrollTop: $(target).offset().top
            }, 1000);

        });
    }


    /*===========================================
        =            Search Active            =
    =============================================*/
    function popupSarchBox($searchBox, $searchOpen, $searchCls, $toggleCls) {
        $($searchOpen).on("click", function (e) {
            e.preventDefault();
            $($searchBox).addClass($toggleCls);
        });
        $($searchBox).on("click", function (e) {
            e.stopPropagation();
            $($searchBox).removeClass($toggleCls);
        });
        $($searchBox)
            .find("form")
            .on("click", function (e) {
                e.stopPropagation();
                $($searchBox).addClass($toggleCls);
            });
        $($searchCls).on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            $($searchBox).removeClass($toggleCls);
        });
    }
    popupSarchBox(
        ".popup-search-box",
        ".searchBoxToggler",
        ".searchClose",
        "show"
    );


    /*=============================================
    =     Offcanvas Menu      =
    =============================================*/
    $(".menu-tigger").on("click", function () {
        $(".offCanvas__info, .offCanvas__overly").addClass("active");
        return false;
    });
    $(".menu-close, .offCanvas__overly").on("click", function () {
        $(".offCanvas__info, .offCanvas__overly").removeClass("active");
    });


    /*=============================================
        =          Swiper active              =
    =============================================*/
    $('.tg-swiper__slider').each(function () {
        var thmSwiperSlider = $(this);
        var settings = $(this).data('swiper-options');

        // Store references to the navigation and pagination elements
        var prevArrow = thmSwiperSlider.find('.slider-prev');
        var nextArrow = thmSwiperSlider.find('.slider-next');
        var paginationEl = thmSwiperSlider.find('.slider-pagination');
        var customPaginationEl = thmSwiperSlider.find('.slider-pagination2'); // Custom number pagination container

        var autoplayCondition = settings['autoplay'];

        var sliderDefault = {
            slidesPerView: 1,
            spaceBetween: settings['spaceBetween'] ? settings['spaceBetween'] : 24,
            loop: settings['loop'] === false ? false : true,
            speed: settings['speed'] ? settings['speed'] : 1000,
            autoplay: autoplayCondition ? autoplayCondition : { delay: 6000, disableOnInteraction: false },
            navigation: {
                nextEl: nextArrow.get(0),
                prevEl: prevArrow.get(0),
            },
            pagination: {
                el: paginationEl.get(0),
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '" aria-label="Go to Slide ' + (index + 1) + '"></span>';
                },
            },
            on: {
                init: function () {
                    updateFractionPagination(this); // Update fraction pagination on init
                    updateCustomNumberPagination(this, customPaginationEl); // Update custom number pagination on init
                },
                slideChange: function () {
                    updateFractionPagination(this); // Update fraction pagination on slide change
                    updateCustomNumberPagination(this, customPaginationEl); // Update custom number pagination on slide change
                }
            },
        };

        var options = JSON.parse(thmSwiperSlider.attr('data-swiper-options'));
        options = $.extend({}, sliderDefault, options);
        var swiper = new Swiper(thmSwiperSlider.get(0), options); // Assign the swiper variable

        if ($('.slider-area').length > 0) {
            $('.slider-area').closest(".container").parent().addClass("arrow-wrap");
        }
    });

    // Function to update fraction pagination
    function updateFractionPagination(swiper) {
        var current = swiper.realIndex + 1; // realIndex gives the current slide
        var total = swiper.slides.length - swiper.loopedSlides * 2; // Adjust for looped slides
        var paginationElement = swiper.pagination.el;

        // Update fraction pagination with current/total
        $(paginationElement).find('.fraction-pagination').html(current + ' / ' + total);
    }

    // Function to update custom number pagination with leading zeros
    function updateCustomNumberPagination(swiper, customPaginationEl) {
        var current = swiper.realIndex + 1; // Get the current slide index
        var total = swiper.slides.length - swiper.loopedSlides * 0; // Adjust for looped slides

        // Create custom pagination HTML with leading zeros
        var customPaginationHTML = '';
        for (var i = 1; i <= total; i++) {
            var isActive = i === current ? 'active' : ''; // Highlight the current slide
            var formattedNumber = i.toString().padStart(2, '0'); // Add leading zero
            customPaginationHTML += `<span class="custom-page ${isActive}" data-slide="${i}">${formattedNumber}</span>`;
        }

        // Update the custom pagination element
        customPaginationEl.html(customPaginationHTML);

        // Add click event to custom pagination numbers
        customPaginationEl.find('.custom-page').on('click', function () {
            var targetSlide = $(this).data('slide') - 1; // Convert to zero-based index
            swiper.slideToLoop(targetSlide); // Slide to the target index (adjust for loop)
        });
    }

    // Function to add animation classes
    function animationProperties() {
        $('[data-ani]').each(function () {
            var animationName = $(this).data('ani');
            $(this).addClass(animationName);
        });

        $('[data-ani-delay]').each(function () {
            var delayTime = $(this).data('ani-delay');
            $(this).css('animation-delay', delayTime);
        });
    }
    animationProperties();

    // Add click event handlers for external slider arrows based on data attributes
    $('[data-slider-prev], [data-slider-next]').on('click', function () {
        var sliderSelector = $(this).data('slider-prev') || $(this).data('slider-next');
        var targetSlider = $(sliderSelector);

        if (targetSlider.length) {
            var swiper = targetSlider[0].swiper;

            if (swiper) {
                if ($(this).data('slider-prev')) {
                    swiper.slidePrev();
                } else {
                    swiper.slideNext();
                }
            }
        }
    });

    /*=============================================
        =    		Magnific Popup		      =
    =============================================*/
    $('.popup-image').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });

    /* magnificPopup video view */
    $('.popup-video').magnificPopup({
        type: 'iframe'
    });


    /*=============================================
        =    		 Wow Active  	         =
    =============================================*/
    function wowAnimation() {
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animated',
            offset: 0,
            mobile: false,
            live: true
        });
        wow.init();
    }

    /*=============================================
        =           Aos Active       =
    =============================================*/
    function aosAnimation() {
        AOS.init({
            duration: 1000,
            mirror: true,
            once: true,
            disable: 'mobile',
        });
    }

    /*=============================================
        =           Counter Number       =
    =============================================*/
    $(".counter-number").counterUp({
        delay: 10,
        time: 1000,
    });

    /*=============================================
        =           Progress Counter       =
    =============================================*/
    $('.progress-bar').waypoint(function () {
        $('.progress-bar').css({
            animation: "animate-positive 1.8s",
            opacity: "1"
        });
    }, { offset: '100%' });


    /*=============================================
        =           Masonary Active       =
    =============================================*/
    $(".masonary-active").imagesLoaded(function () {
        var $filter = ".masonary-active",
            $filterItem = ".filter-item",
            $filterMenu = ".filter-menu-active";

        if ($($filter).length > 0) {
            var $grid = $($filter).isotope({
                itemSelector: $filterItem,
                filter: "*",
                masonry: {
                    // use outer width of grid-sizer for columnWidth
                    columnWidth: 1,
                },
            });

            // filter items on button click
            $($filterMenu).on("click", "button", function () {
                var filterValue = $(this).attr("data-filter");
                $grid.isotope({
                    filter: filterValue,
                });
            });

            // Menu Active Class
            $($filterMenu).on("click", "button", function (event) {
                event.preventDefault();
                $(this).addClass("active");
                $(this).siblings(".active").removeClass("active");
            });
        }
    });

    /*=============================================
        =           Date Time Picker       =
    =============================================*/
    // Only Date Picker
    $('.date-pick').datetimepicker({
        timepicker: false,
        datepicker: true,
        format: 'd-m-y',
        step: 10
    });

    // Only Time Picker
    $('.time-pick').datetimepicker({
        datepicker: false,
        format: 'H:i',
        step: 30
    });

    // Date Time
    $('.date-time-pick').datetimepicker({

    });



    /*=============================================
        =           Gsap text Animation       =
    =============================================*/
    if ($('.text-anim').length) {
        let staggerAmount = 0.05,
            translateXValue = 20,
            delayValue = 0.5,
            easeType = "power2.out",
            animatedTextElements = document.querySelectorAll('.text-anim');

        animatedTextElements.forEach((element) => {
            let animationSplitText = new SplitText(element, {
                type: "chars, words"
            });
            gsap.from(animationSplitText.chars, {
                duration: 1,
                delay: delayValue,
                x: translateXValue,
                autoAlpha: 0,
                stagger: staggerAmount,
                ease: easeType,
                scrollTrigger: {
                    trigger: element,
                    start: "top 85%"
                },
            });
        });
    }

    if ($('.text-anim2').length) {
        let staggerAmount = 0.03,
            translateXValue = 20,
            delayValue = 0.1,
            easeType = "power2.out",
            animatedTextElements = document.querySelectorAll('.text-anim2');

        animatedTextElements.forEach((element) => {
            let animationSplitText = new SplitText(element, { type: "chars, words" });
            gsap.from(animationSplitText.chars, {
                duration: 1,
                delay: delayValue,
                x: translateXValue,
                autoAlpha: 0,
                stagger: staggerAmount,
                ease: easeType,
                scrollTrigger: { trigger: element, start: "top 85%" },
            });
        });
    }


})(jQuery);

 // Support Ticket Modal functionality with improved performance
 document.addEventListener('DOMContentLoaded', function() {
    // Cache DOM elements
    const modal = document.getElementById('supportTicketModal');
    const openBtn = document.getElementById('openSupportTicket');
    const closeBtn = document.getElementById('closeTicketModal');
    const overlay = document.getElementById('modalOverlay');
    const body = document.body;
    
    // Custom select elements
    const customSelect = document.getElementById('customSelect');
    const customSelectTrigger = document.getElementById('customSelectTrigger');
    const hiddenSelect = document.getElementById('service');
    const customOptions = document.querySelectorAll('.custom-option');
    
    // File input elements
    const fileInput = document.getElementById('attachment');
    const fileName = document.getElementById('fileName');
    
    // Toggle modal visibility
    function toggleModal() {
        const isVisible = modal.style.display === 'block';
        modal.style.display = isVisible ? 'none' : 'block';
        body.style.overflow = isVisible ? '' : 'hidden';
    }
    
    // Modal event listeners
    openBtn.addEventListener('click', toggleModal);
    closeBtn.addEventListener('click', toggleModal);
    overlay.addEventListener('click', toggleModal);
    
    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            toggleModal();
        }
    });
    
    // Prevent modal closing when clicking the container
    document.querySelector('.modal-container').addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Custom select functionality
    customSelectTrigger.addEventListener('click', function(e) {
        e.preventDefault();
        customSelect.classList.toggle('opened');
    });
    
    // Close custom select when clicking outside
    document.addEventListener('click', function(e) {
        if (!customSelect.contains(e.target)) {
            customSelect.classList.remove('opened');
        }
    });
    
    // Handle option selection
    customOptions.forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            
            // Update hidden select
            const options = hiddenSelect.options;
            for (let i = 0; i < options.length; i++) {
                if (options[i].value === value) {
                    hiddenSelect.selectedIndex = i;
                    break;
                }
            }
            
            // Update custom select visual
            customSelectTrigger.textContent = this.textContent;
            
            // Remove previous selection
            customOptions.forEach(opt => opt.classList.remove('selection'));
            
            // Add selection class
            this.classList.add('selection');
            
            // Close dropdown
            customSelect.classList.remove('opened');
        });
    });
    
    // Initialize selected value if exists
    if (hiddenSelect.value) {
        const selectedOption = Array.from(customOptions).find(
            option => option.getAttribute('data-value') === hiddenSelect.value
        );
        
        if (selectedOption) {
            customSelectTrigger.textContent = selectedOption.textContent;
            selectedOption.classList.add('selection');
        }
    }
    
    // File input handling
    fileInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            fileName.textContent = this.files[0].name;
        } else {
            fileName.textContent = 'No file selected';
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Override Bootstrap's default button color with our green theme
    const style = document.createElement('style');
    style.textContent = `
        .btn-primary, .btn-primary:focus, .btn-primary:active, .btn-primary:disabled {
            background-color: #216115 !important;
            border-color: #216115 !important;
        }
        .btn-primary:hover {
            background-color: #174b0e !important;
            border-color: #174b0e !important;
        }
        .spinner-border {
            border-color: #fff;
            border-right-color: transparent;
        }
    `;
    document.head.appendChild(style);
    
    // DOM Elements
    const passwordToggles = document.querySelectorAll('.password-toggle');
    const userPasswordInput = document.getElementById('userPassword');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const userEmailInput = document.getElementById('userEmail');
    const fullNameInput = document.getElementById('fullName');
    const registerForm = document.getElementById('registerForm');
    const passwordStrengthIndicator = document.querySelector('.password-strength');
    const passwordMatchIndicator = document.querySelector('.password-match');
    
    // Constants
    const EMAIL_REGEX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    const MIN_PASSWORD_LENGTH = 8;
    const DEBOUNCE_DELAY = 300;
    
    // Toggle password visibility for both password fields
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const passwordField = this.parentElement.querySelector('input');
            const passwordIcon = this.querySelector('i');
            const isPasswordVisible = passwordField.type === 'password';
            
            passwordField.type = isPasswordVisible ? 'text' : 'password';
            passwordIcon.classList.toggle('fa-eye');
            passwordIcon.classList.toggle('fa-eye-slash');
            
            // Accessibility improvement
            this.setAttribute('aria-label', isPasswordVisible ? 'Hide password' : 'Show password');
        });
        
        // Keyboard accessibility for password toggle
        toggle.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
    
    // Real-time password strength feedback with debounce
    let debounceTimeout;
    userPasswordInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const password = userPasswordInput.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrengthIndicator(strength);
            checkPasswordMatch();
        }, DEBOUNCE_DELAY);
    });
    
    // Check password match in real-time
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    
    function checkPasswordMatch() {
        const password = userPasswordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (!password || !confirmPassword) {
            passwordMatchIndicator.textContent = '';
            passwordMatchIndicator.className = 'password-match';
            return;
        }
        
        if (password === confirmPassword) {
            passwordMatchIndicator.textContent = 'Passwords match';
            passwordMatchIndicator.className = 'password-match text-match';
        } else {
            passwordMatchIndicator.textContent = 'Passwords do not match';
            passwordMatchIndicator.className = 'password-match text-mismatch';
        }
    }
    
    // Form submission handler
    registerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const isFormValid = validateRegisterForm();
        
        if (isFormValid) {
            initiateRegistrationProcess();
        }
    });
    
    // Remove validation styling on input
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        // Add focus effect to form fields
        input.addEventListener('focus', function() {
            this.style.borderColor = 'var(--primary)';
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.style.borderColor = 'var(--border-color)';
            }
        });
    });
    
    // Terms checkbox validation
    const termsCheckbox = document.getElementById('acceptTerms');
    termsCheckbox.addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });
    
    // Helper functions
    function validateRegisterForm() {
        let isValid = true;
        const fullName = fullNameInput.value.trim();
        const userEmail = userEmailInput.value.trim();
        const userPassword = userPasswordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();
        const termsAccepted = termsCheckbox.checked;
        
        // Validate full name
        if (fullName.length < 2) {
            fullNameInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate email
        if (!EMAIL_REGEX.test(userEmail)) {
            userEmailInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate password
        if (userPassword.length < MIN_PASSWORD_LENGTH) {
            userPasswordInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate password match
        if (userPassword !== confirmPassword) {
            confirmPasswordInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate terms
        if (!termsAccepted) {
            termsCheckbox.classList.add('is-invalid');
            isValid = false;
        }
        
        return isValid;
    }
    
    function initiateRegistrationProcess() {
        const registerButton = document.querySelector('.register-btn');
        const originalButtonContent = registerButton.innerHTML;
        
        // Show loading state
        registerButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Creating Account...
        `;
        registerButton.disabled = true;
        
        // In a real application, this would be a fetch or XMLHttpRequest to your registration endpoint
        simulateRegistrationRequest()
            .then(() => {
                // On success, redirect to dashboard or verification page
                window.location.href = 'registration-success.html';
            })
            .catch(error => {
                // Show error message
                showRegisterError(error.message);
                registerButton.innerHTML = originalButtonContent;
                registerButton.disabled = false;
            });
    }
    
    function simulateRegistrationRequest() {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Simulate server response
                const shouldFail = Math.random() < 0.1; // 10% chance of failure for demo
                
                if (shouldFail) {
                    reject(new Error('Email address is already registered. Please use a different email.'));
                } else {
                    resolve();
                }
            }, 2000);
        });
    }
    
    function showRegisterError(message) {
        // Remove any existing error messages
        const existingErrors = document.querySelectorAll('.register-error');
        existingErrors.forEach(error => error.remove());
        
        // Create error element
        const errorElement = document.createElement('div');
        errorElement.className = 'alert alert-danger register-error mb-4';
        errorElement.setAttribute('role', 'alert');
        errorElement.innerHTML = `
            <i class="fas fa-exclamation-circle me-2"></i>
            ${message}
        `;
        
        // Add error message at the top of the form
        registerForm.insertAdjacentElement('afterbegin', errorElement);
    }
    
    function calculatePasswordStrength(password) {
        if (!password) return 0;
        
        let strength = 0;
        
        // Length contributes up to 40% of strength
        strength += Math.min(password.length / MIN_PASSWORD_LENGTH * 40, 40);
        
        // Mixed case contributes 20%
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
            strength += 20;
        }
        
        // Numbers contribute 20%
        if (/\d/.test(password)) {
            strength += 20;
        }
        
        // Special chars contribute 20%
        if (/[^a-zA-Z0-9]/.test(password)) {
            strength += 20;
        }
        
        return Math.min(strength, 100);
    }
    
    function updatePasswordStrengthIndicator(strength) {
        let strengthText = '';
        let strengthClass = '';
        
        if (strength === 0) {
            passwordStrengthIndicator.textContent = '';
            passwordStrengthIndicator.className = 'password-strength';
            return;
        } else if (strength < 40) {
            strengthText = 'Weak';
            strengthClass = 'text-danger';
        } else if (strength < 70) {
            strengthText = 'Moderate';
            strengthClass = 'text-warning';
        } else {
            strengthText = 'Strong';
            strengthClass = 'text-success';
        }
        
        passwordStrengthIndicator.textContent = `Password strength: ${strengthText}`;
        passwordStrengthIndicator.className = `password-strength ${strengthClass}`;
    }
});

    document.addEventListener('DOMContentLoaded', function() {
        // Override Bootstrap's default button color with our green theme
        // This fixes the blue flash when buttons are clicked
        const style = document.createElement('style');
        style.textContent = `
            .btn-primary, .btn-primary:focus, .btn-primary:active, .btn-primary:disabled {
                background-color: #216115 !important;
                border-color: #216115 !important;
            }
            .btn-primary:hover {
                background-color: #174b0e !important;
                border-color: #174b0e !important;
            }
            .spinner-border {
                border-color: #fff;
                border-right-color: transparent;
            }
        `;
        document.head.appendChild(style);
        
        // DOM Elements
        const passwordToggle = document.querySelector('.password-toggle');
        const userPasswordInput = document.getElementById('userPassword');
        const userEmailInput = document.getElementById('userEmail');
        const loginForm = document.getElementById('loginForm');
        const passwordStrengthIndicator = document.querySelector('.password-strength');
        
        // Constants
        const EMAIL_REGEX = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        const MIN_PASSWORD_LENGTH = 8;
        const DEBOUNCE_DELAY = 300;
        
        // Toggle password visibility
        passwordToggle.addEventListener('click', function() {
            const passwordIcon = this.querySelector('i');
            const isPasswordVisible = userPasswordInput.type === 'password';
            
            userPasswordInput.type = isPasswordVisible ? 'text' : 'password';
            passwordIcon.classList.toggle('fa-eye');
            passwordIcon.classList.toggle('fa-eye-slash');
            
            // Accessibility improvement
            this.setAttribute('aria-label', isPasswordVisible ? 'Hide password' : 'Show password');
        });
        
        // Keyboard accessibility for password toggle
        passwordToggle.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Real-time password strength feedback with debounce
        let debounceTimeout;
        userPasswordInput.addEventListener('input', function() {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(() => {
                const password = userPasswordInput.value;
                const strength = calculatePasswordStrength(password);
                updatePasswordStrengthIndicator(strength);
            }, DEBOUNCE_DELAY);
        });
        
        // Form submission handler
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const isFormValid = validateLoginForm();
            
            if (isFormValid) {
                initiateLoginProcess();
            }
        });
        
        // Remove validation styling on input
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
            
            // Add focus effect to form fields
            input.addEventListener('focus', function() {
                this.style.borderColor = 'var(--primary)';
            });
            
            input.addEventListener('blur', function() {
                if (this.value === '') {
                    this.style.borderColor = 'var(--border-color)';
                }
            });
        });
        
        // Helper functions
        function validateLoginForm() {
            let isValid = true;
            const userEmail = userEmailInput.value.trim();
            const userPassword = userPasswordInput.value.trim();
            
            // Validate email
            if (!EMAIL_REGEX.test(userEmail)) {
                userEmailInput.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate password
            if (userPassword.length < MIN_PASSWORD_LENGTH) {
                userPasswordInput.classList.add('is-invalid');
                isValid = false;
            }
            
            return isValid;
        }
        
        function initiateLoginProcess() {
            const loginButton = document.querySelector('.login-btn');
            const originalButtonContent = loginButton.innerHTML;
            
            // Show loading state
            loginButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Authenticating...
            `;
            loginButton.disabled = true;
            
            // In a real application, this would be a fetch or XMLHttpRequest to your authentication endpoint
            simulateAuthenticationRequest()
                .then(() => {
                    // On success, redirect to dashboard
                    window.location.href = 'index.html';
                })
                .catch(error => {
                    // Show error message
                    showLoginError(error.message);
                    loginButton.innerHTML = originalButtonContent;
                    loginButton.disabled = false;
                });
        }
        
        function simulateAuthenticationRequest() {
            return new Promise((resolve, reject) => {
                setTimeout(() => {
                    // Simulate server response
                    const shouldFail = Math.random() < 0.2; // 20% chance of failure for demo
                    
                    if (shouldFail) {
                        reject(new Error('Invalid credentials. Please try again.'));
                    } else {
                        resolve();
                    }
                }, 1500);
            });
        }
        
        function showLoginError(message) {
            // Remove any existing error messages
            const existingErrors = document.querySelectorAll('.login-error');
            existingErrors.forEach(error => error.remove());
            
            // Create error element
            const errorElement = document.createElement('div');
            errorElement.className = 'alert alert-danger login-error mb-4';
            errorElement.setAttribute('role', 'alert');
            errorElement.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i>
                ${message}
            `;
            
            // Add error message at the top of the form
            loginForm.insertAdjacentElement('afterbegin', errorElement);
        }
        
        function calculatePasswordStrength(password) {
            if (!password) return 0;
            
            let strength = 0;
            
            // Length contributes up to 40% of strength
            strength += Math.min(password.length / MIN_PASSWORD_LENGTH * 40, 40);
            
            // Mixed case contributes 20%
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) {
                strength += 20;
            }
            
            // Numbers contribute 20%
            if (/\d/.test(password)) {
                strength += 20;
            }
            
            // Special chars contribute 20%
            if (/[^a-zA-Z0-9]/.test(password)) {
                strength += 20;
            }
            
            return Math.min(strength, 100);
        }
        
        function updatePasswordStrengthIndicator(strength) {
            let strengthText = '';
            let strengthClass = '';
            
            if (strength === 0) {
                passwordStrengthIndicator.textContent = '';
                passwordStrengthIndicator.className = 'password-strength';
                return;
            } else if (strength < 40) {
                strengthText = 'Weak';
                strengthClass = 'text-danger';
            } else if (strength < 70) {
                strengthText = 'Moderate';
                strengthClass = 'text-warning';
            } else {
                strengthText = 'Strong';
                strengthClass = 'text-success';
            }
            
            passwordStrengthIndicator.textContent = `Password strength: ${strengthText}`;
            passwordStrengthIndicator.className = `password-strength ${strengthClass}`;
        }
    });

    // In your main JS file
$(document).ready(function() {
    $('.footer__newsletter-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const button = form.find('button[type="submit"]');
        
        button.prop('disabled', true);
        
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                form.find('input').val('');
                alert(response.success || 'Thank you for subscribing!');
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                if (errors && errors.email) {
                    alert(errors.email[0]);
                } else {
                    alert('An error occurred. Please try again.');
                }
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
});