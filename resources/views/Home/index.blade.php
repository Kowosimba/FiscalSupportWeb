@extends('components.homelayout')
@section('home-content')

<style>
    .swiper-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 100%);
    z-index: 1;
}

.hero-style1 {
    position: relative;
    z-index: 2;
}

.hero-style1 .sub-title,
.hero-style1 .hero-title,
.hero-style1 .hero-text {
    color: #ffffff !important;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.hero-style1 .sub-title span {
    color: #00ff88 !important; /* Keep your brand accent color for "Welcome!" */
}

</style>
<div class="tg-header-contact-info d-lg-block d-none">
    <div class="container">
        <div class="info-card-wrap style3">
            <div class="info-card">
                <div class="info-card_icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="info-card_content">
                    <a href="mailto:sales@fiscalsupportservices.com" class="info-card_link">sales@fiscalsupportservices.com</a>
                </div>
            </div>
            <div class="divider"></div>
            <div class="info-card">
                <div class="info-card_icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div class="info-card_content">
                    <a href="tel:+263292270666" class="info-card_link">+263292270666</a>
                </div>
            </div>
            <div class="divider"></div>
            <div class="info-card">
                <div class="info-card_icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-card_content">
                    <p class="info-card_link">Mon-Fri: 8:00-17:00</p>
                </div>
            </div>
            <div class="divider"></div>
            <div class="info-card">
                <div class="info-card_icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-card_content">
                    <p class="info-card_link">36 East Road, Harare.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="offCanvas__info">
    <div class="offCanvas__close-icon menu-close">
        <button><i class="fas fa-times"></i></button>
    </div>
    <div class="appointment-wrap2">
        <div class="appointment-thumb2 d-lg-block d-none">
            <img src="assets/img/others/ticket.jpg" alt="img">
        </div>
        <div class="row justify-content-end">
            <div class="col-lg-6">
                <div class="appointment-form-wrap">
                    <div class="section__title mb-30">
                        <h4 class="title">Create A Ticket</h4>
                    </div>
                    <form action="submit" method="POST" class="appointment__form ajax-contact">
                        <div class="row gy-20">
                            <!-- Company Name -->
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <input type="text" class="form-control style-border3" name="company_name" id="company_name" placeholder="Company Name">
                                    <label class="form-icon-right2">
                                        <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7 8C4.78125 8 3 6.21875 3 4C3 1.8125 4.78125 0 7 0C9.1875 0 11 1.8125 11 4C11 6.21875 9.1875 8 7 8ZM7 1C5.34375 1 4 2.375 4 4C4 5.65625 5.34375 7 7 7C8.625 7 10 5.65625 10 4C10 2.375 8.625 1 7 1ZM8.5625 9.5C11.5625 9.5 14 11.9375 14 14.9375C14 15.5312 13.5 16 12.9062 16H1.0625C0.46875 16 0 15.5312 0 14.9375C0 11.9375 2.40625 9.5 5.40625 9.5H8.5625ZM12.9062 15C12.9375 15 13 14.9688 13 14.9375C13 12.5 11 10.5 8.5625 10.5H5.40625C2.96875 10.5 1 12.5 1 14.9375C1 14.9688 1.03125 15 1.0625 15H12.9062Z" fill="currentColor" />
                                        </svg>
                                    </label>
                                </div>
                            </div>
        
                            <!-- Email Address -->
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <input type="text" class="form-control style-border3" name="email" id="email" placeholder="Email Address">
                                    <label class="form-icon-right2">
                                        <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 2C0 0.90625 0.875 0 2 0H14C15.0938 0 16 0.90625 16 2V10C16 11.125 15.0938 12 14 12H2C0.875 12 0 11.125 0 10V2ZM1 2V3.25L7.09375 7.75C7.625 8.125 8.34375 8.125 8.875 7.75L15 3.25V2C15 1.46875 14.5312 1 14 1H1.96875C1.4375 1 0.96875 1.46875 0.96875 2H1ZM1 4.5V10C1 10.5625 1.4375 11 2 11H14C14.5312 11 15 10.5625 15 10V4.5L9.46875 8.5625C8.59375 9.1875 7.375 9.1875 6.5 8.5625L1 4.5Z" fill="currentColor" />
                                        </svg>
                                    </label>
                                </div>
                            </div>
        
                            <!-- Phone Number -->
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <input type="text" class="form-control style-border3" name="phone" id="phone" placeholder="Phone Number">
                                    <label class="form-icon-right2">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M14.5 11.375V14.125C14.5 14.625 14.125 15 13.625 15C6.125 15 0 8.875 0 1.375C0 0.875 0.375 0.5 0.875 0.5H3.625C4.125 0.5 4.5 0.875 4.5 1.375C4.5 2.125 4.5625 2.875 4.75 3.5625C4.8125 3.875 4.75 4.1875 4.5 4.4375L3.125 5.8125C4.3125 8.125 6.875 10.6875 9.1875 11.875L10.5625 10.5C10.8125 10.25 11.125 10.1875 11.4375 10.25C12.125 10.4375 12.875 10.5 13.625 10.5C14.125 10.5 14.5 10.875 14.5 11.375Z" fill="currentColor" />
                                        </svg>
                                    </label>
                                </div>
                            </div>
        
                            <!-- AnyDesk Address -->
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <input type="text" class="form-control style-border3" name="anydesk" id="anydesk" placeholder="AnyDesk Address">
                                    <label class="form-icon-right2">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 0C3.6 0 0 3.6 0 8C0 12.4 3.6 16 8 16C12.4 16 16 12.4 16 8C16 3.6 12.4 0 8 0ZM8 14C4.7 14 2 11.3 2 8C2 4.7 4.7 2 8 2C11.3 2 14 4.7 14 8C14 11.3 11.3 14 8 14ZM11.5 5.5L10.5 4.5L8 7L5.5 4.5L4.5 5.5L7 8L4.5 10.5L5.5 11.5L8 9L10.5 11.5L11.5 10.5L9 8L11.5 5.5Z" fill="currentColor" />
                                        </svg>
                                    </label>
                                </div>
                            </div>
        
                            <!-- Select Service -->
                            <div class="col-12">
                                <div class="form-group">
                                    <select name="subject" id="subject" class="form-select style-border3">
                                        <option value="" disabled selected hidden>Select Service</option>
                                        <option value="Reporting a fault or problem">Reporting a Fault or Problem</option>
                                        <option value="Templates training to new systems">Templates Training to New Systems</option>
                                        <option value="Virtual Online Fiscalisation Solution">Virtual Online Fiscalisation Solution</option>
                                        <option value="Personal to a certain technician">Personal to a Certain Technician</option>
                                        <option value="Software Installation">Software Installation</option>
                                        <option value="Hardware Support">Hardware Support</option>
                                        <option value="Network Issues">Network Issues</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <label class="form-icon-right2">
                                        <svg width="16" height="8" viewBox="0 0 16 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.125 0.6875C1.21875 0.5625 1.34375 0.5 1.5 0.5C1.59375 0.5 1.71875 0.53125 1.8125 0.625L7.96875 6.28125L14.1562 0.625C14.3438 0.4375 14.6562 0.4375 14.8438 0.65625C15.0312 0.84375 15.0312 1.15625 14.8125 1.34375L8.3125 7.34375C8.125 7.53125 7.84375 7.53125 7.65625 7.34375L1.15625 1.34375C0.9375 1.1875 0.9375 0.875 1.125 0.6875Z" fill="currentColor" />
                                        </svg>
                                    </label>
                                </div>
                            </div>
        
                            <!-- Message -->
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea name="message" placeholder="Type Your Message" id="contactForm" class="form-control style-border3"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn mt-30">
                            <span class="btn-text" data-text="Submit Ticket"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="offCanvas__overly"></div>


<!--==============================
    Hero Area
    ==============================-->
    <section class="hero-wrapper hero-1">
        <div class="hero-slider1 overflow-hidden">
            <div class="tg-swiper__slider swiper-container" id="heroSlider1" data-swiper-options='{
                "effect": "fade",
                "slidesPerView": "1",
                "autoHeight": "true"
            }'>
                <div class="swiper-wrapper">
                    <!-- Slide 1: Fiscal Support Services Overview -->
                    <div class="swiper-slide" data-background="assets/img/hero/slide2.jpg">
                        <div class="hero-bg-shape1-1"></div>
                        <div class="hero-bg-shape1-2"></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="hero-style1">
                                        <div class="sub-title" data-ani="slideinup" data-ani-delay="0.1s">
                                            <span>Welcome!</span> Empowering Businesses Through Technology
                                        </div>
                                        <h1 class="hero-title">
                                            <div class="title1" data-ani="slideinup" data-ani-delay="0.2s">Reliable</div>
                                            <div class="title2" data-ani="slideinup" data-ani-delay="0.3s">Fiscal Device</div>
                                            <div class="title3" data-ani="slideinup" data-ani-delay="0.4s">Support</div>
                                        </h1>
                                        <p class="hero-text" data-ani="slideinup" data-ani-delay="0.5s">
                                            With over 25 years of collective experience, we provide cost-effective and efficient technology solutions for fiscal devices, ensuring your business operates seamlessly.
                                        </p>
                                        <div class="tg-button-wrap" data-ani="slideinup" data-ani-delay="0.6s">
                                            <a href="{{ route('contact') }}" class="btn btn-three">
                                                <span class="btn-text" data-text="Get Support"></span>
                                            </a>
                                            <a href="{{ route('services.index') }}" class="btn btn-four">
                                                <span class="btn-text" data-text="Our Services"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Slide 2: Technology Consulting Services -->
                    <div class="swiper-slide" data-background="assets/img/hero/hero-bg4-2.png">
                        <div class="hero-bg-shape1-1"></div>
                        <div class="hero-bg-shape1-2"></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="hero-style1">
                                        <div class="sub-title" data-ani="slideinup" data-ani-delay="0.1s">
                                            <span>Welcome!</span> Strategic Technology Consulting
                                        </div>
                                        <h1 class="hero-title">
                                            <div class="title1" data-ani="slideinup" data-ani-delay="0.2s">Aligning</div>
                                            <div class="title2" data-ani="slideinup" data-ani-delay="0.3s">Technology</div>
                                            <div class="title3" data-ani="slideinup" data-ani-delay="0.4s">With Business</div>
                                        </h1>
                                        <p class="hero-text" data-ani="slideinup" data-ani-delay="0.5s">
                                            We help SMEs align their IT investments with business processes, enabling growth and operational control. Our expertise spans across Zimbabwe, Botswana, Namibia, Kenya, and South Africa.
                                        </p>
                                        <div class="tg-button-wrap" data-ani="slideinup" data-ani-delay="0.6s">
                                            <a href="{{ route('contact') }}" class="btn btn-three">
                                                <span class="btn-text" data-text="Get Consultant"></span>
                                            </a>
                                            <a href="{{ route('services.index') }}" class="btn btn-four">
                                                <span class="btn-text" data-text="Our Services"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- Slide 3: Pastel and Fiscal Solutions -->
                    <div class="swiper-slide" data-background="assets/img/hero/slide3.png">
                        <div class="hero-bg-shape1-1"></div>
                        <div class="hero-bg-shape1-2"></div>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="hero-style1">
                                        <div class="sub-title" data-ani="slideinup" data-ani-delay="0.1s">
                                            <span>Welcome!</span> Comprehensive Fiscal Solutions
                                        </div>
                                        <h1 class="hero-title">
                                            <div class="title1" data-ani="slideinup" data-ani-delay="0.2s">Virtual Online</div>
                                            <div class="title2" data-ani="slideinup" data-ani-delay="0.3s">Fiscalisation</div>
                                            <div class="title3" data-ani="slideinup" data-ani-delay="0.4s">Solutions</div>
                                        </h1>
                                        <p class="hero-text" data-ani="slideinup" data-ani-delay="0.5s">
                                            We offer tailored fiscal solutions, Pastel Accounting Software to ensure your business remains compliant and efficient. Our team is passionate about delivering exceptional service.
                                        </p>
                                        <div class="tg-button-wrap" data-ani="slideinup" data-ani-delay="0.6s">
                                            <a href="{{ route('contact') }}" class="btn btn-three">
                                                <span class="btn-text" data-text="Get Support"></span>
                                            </a>
                                            <a href="{{ route('services.index') }}" class="btn btn-four">
                                                <span class="btn-text" data-text="Our Services"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 </div>
                <div class="slider-pagination2"></div>
            </div>
        </div>
    </section>
    <!--======== / Hero Section ========-->

<!--==============================
    Service Area
    ==============================-->
<section class="service-area-2 pt-120 pb-120 position-relative">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="section__title text-center mb-50">
                    <span class="sub-title text-anim">Our Services</span>
                    <h2 class="title text-anim2">Empowering Businesses Through Technology Solutions</h2>
                </div>
            </div>
        </div>
        <div class="row gy-30 justify-content-center">
            @foreach($services->take(3) as $service)
            <div class="col-lg-4 col-md-6">
                <div class="service-card style2">
                    <div class="box-img image-anim">
                        <img src="{{ $service->image ? asset('storage/' . $service->image) : '/assets/img/service/service-img-2-3.jpg' }}" alt="{{ $service->title }}">
                    </div>
                    <div class="box-content">
                        <div class="box-icon">
                            <svg width="57" height="55" viewBox="0 0 57 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M36.0088 13.9465C37.5286 13.9465 38.765 12.7101 38.765 11.1904C38.765 9.67066 37.5286 8.43414 36.0088 8.43414C34.4891 8.43414 33.2527 9.67066 33.2527 11.1904C33.2527 11.8111 33.4591 12.3844 33.8067 12.8456L29.2782 19.1738C29.008 19.0851 28.7197 19.0364 28.4201 19.0364C27.6959 19.0364 27.0364 19.3176 26.544 19.776L23.5256 18.0632C23.5658 17.8763 23.5874 17.6824 23.5874 17.4837C23.5874 15.9639 22.351 14.7275 20.8313 14.7275C19.3115 14.7275 18.0751 15.9639 18.0751 17.4837C18.0751 17.6824 18.0967 17.8763 18.137 18.0632L15.1186 19.776C14.6262 19.3176 13.9667 19.0365 13.2425 19.0365C11.7228 19.0365 10.4864 20.273 10.4864 21.7927C10.4864 23.3125 11.7228 24.549 13.2425 24.549C14.7622 24.549 15.9987 23.3125 15.9987 21.7927C15.9987 21.594 15.977 21.4002 15.9368 21.2132L18.9552 19.5004C19.4476 19.9588 20.1071 20.24 20.8313 20.24C21.5555 20.24 22.2149 19.9588 22.7073 19.5005L25.7258 21.2133C25.6855 21.4003 25.6639 21.5941 25.6639 21.7928C25.6639 23.3126 26.9003 24.5491 28.4201 24.5491C29.9399 24.5491 31.1763 23.3126 31.1763 21.7928C31.1763 21.1721 30.9698 20.5987 30.6222 20.1374L35.1507 13.8092C35.4208 13.8978 35.7092 13.9465 36.0088 13.9465ZM36.0088 10.0878C36.6167 10.0878 37.1113 10.5824 37.1113 11.1904C37.1113 11.7983 36.6167 12.2929 36.0088 12.2929C35.4009 12.2929 34.9064 11.7983 34.9064 11.1904C34.9063 10.5824 35.4008 10.0878 36.0088 10.0878ZM13.2422 22.8951C12.6343 22.8951 12.1397 22.4005 12.1397 21.7925C12.1397 21.1845 12.6343 20.6899 13.2422 20.6899C13.8501 20.6899 14.3446 21.1845 14.3446 21.7925C14.3446 22.4005 13.8501 22.8951 13.2422 22.8951ZM20.8311 18.5861C20.2232 18.5861 19.7286 18.0916 19.7286 17.4836C19.7286 16.8757 20.2232 16.3811 20.8311 16.3811C21.439 16.3811 21.9335 16.8757 21.9335 17.4836C21.9336 18.0916 21.439 18.5861 20.8311 18.5861ZM28.4199 22.8951C27.812 22.8951 27.3175 22.4005 27.3175 21.7925C27.3175 21.1845 27.812 20.6899 28.4199 20.6899C29.028 20.6899 29.5225 21.1845 29.5225 21.7925C29.5225 22.4005 29.0278 22.8951 28.4199 22.8951Z" fill="currentColor" />
                                <path d="M46.1705 40.8358H2.33887C1.96105 40.8358 1.65369 40.5285 1.65369 40.1507V6.0385C1.65369 5.66068 1.96105 5.35332 2.33887 5.35332H6.23672C6.69336 5.35332 7.06356 4.98322 7.06356 4.52647C7.06356 4.06973 6.69336 3.69963 6.23672 3.69963H2.33887C1.04921 3.69963 0 4.74884 0 6.0385V40.1507C0 41.4403 1.04921 42.4895 2.33887 42.4895H8.04001V44.3092C8.04001 45.3459 8.88339 46.1893 9.92003 46.1893H18.1966L14.3005 53.796C14.0924 54.2025 14.253 54.7007 14.6595 54.9089C14.7801 54.9708 14.9089 55 15.0358 55C15.3362 55 15.626 54.8357 15.7723 54.5499L20.0546 46.1893H29.1964L33.4787 54.5499C33.6251 54.8357 33.9148 55 34.2152 55C34.3421 55 34.4709 54.9706 34.5915 54.9089C34.998 54.7007 35.1587 54.2025 34.9505 53.796L31.0544 46.1893H39.331C40.3676 46.1893 41.211 45.346 41.211 44.3092V42.4895H46.1705C46.6272 42.4895 46.9974 42.1194 46.9974 41.6627C46.9974 41.2059 46.6272 40.8358 46.1705 40.8358ZM39.5573 44.3092C39.5573 44.434 39.4558 44.5356 39.331 44.5356H9.92003C9.79523 44.5356 9.6937 44.434 9.6937 44.3092V42.4895H39.5573V44.3092Z" fill="currentColor" />
                                <path d="M9.54409 5.35332H46.912C47.2898 5.35332 47.5972 5.66068 47.5972 6.0385V13.9002C47.5972 14.357 47.9674 14.7271 48.4241 14.7271C48.8807 14.7271 49.2509 14.357 49.2509 13.9002V6.0385C49.2509 4.74884 48.2017 3.69963 46.912 3.69963H29.2146V2.07439C29.2146 0.930585 28.284 0 27.1401 0H22.1109C20.9671 0 20.0364 0.930585 20.0364 2.07439V3.69963H9.54409C9.08745 3.69963 8.71725 4.06973 8.71725 4.52647C8.71725 4.98322 9.08745 5.35332 9.54409 5.35332ZM21.6901 2.07439C21.6901 1.84243 21.8788 1.65369 22.1109 1.65369H27.1401C27.3721 1.65369 27.5609 1.84243 27.5609 2.07439V3.69963H21.6902L21.6901 2.07439Z" fill="currentColor" />
                                <path d="M11.565 28.7761C11.1084 28.7761 10.7382 29.1462 10.7382 29.603V36.9092C10.7382 37.366 11.1084 37.736 11.565 37.736H14.9195C15.3761 37.736 15.7463 37.366 15.7463 36.9092V29.603C15.7463 29.1462 15.3761 28.7761 14.9195 28.7761H11.565ZM14.0926 36.0825H12.3919V30.4299H14.0926V36.0825Z" fill="currentColor" />
                                <path d="M19.1538 24.5506C18.6971 24.5506 18.3269 24.9207 18.3269 25.3775V36.9093C18.3269 37.3661 18.6971 37.7362 19.1538 37.7362H22.5082C22.9649 37.7362 23.3351 37.3661 23.3351 36.9093V25.3775C23.3351 24.9207 22.9649 24.5506 22.5082 24.5506H19.1538ZM21.6815 36.0825H19.9807V26.2043H21.6815V36.0825Z" fill="currentColor" />
                                <path d="M26.7427 28.7761C26.286 28.7761 25.9158 29.1462 25.9158 29.603V36.9092C25.9158 37.366 26.286 37.736 26.7427 37.736H30.0971C30.5538 37.736 30.924 37.366 30.924 36.9092V29.603C30.924 29.1462 30.5538 28.7761 30.0971 28.7761H26.7427ZM29.2703 36.0825H27.5695V30.4299H29.2703V36.0825Z" fill="currentColor" />
                                <path d="M34.3315 33.6552C33.8749 33.6552 33.5047 34.0253 33.5047 34.482V36.9093C33.5047 37.3661 33.8749 37.7362 34.3315 37.7362H37.686C38.1426 37.7362 38.5128 37.3661 38.5128 36.9093C38.5128 36.4526 38.1426 36.0825 37.686 36.0825H35.1584V34.482C35.1584 34.0253 34.7882 33.6552 34.3315 33.6552Z" fill="currentColor" />
                                <path d="M56.0242 41.6135L51.2806 33.3973C52.4824 32.1164 53.3576 30.5512 53.825 28.8067C54.5721 26.0185 54.1887 23.1061 52.7454 20.6062C49.7659 15.4452 43.1433 13.671 37.9827 16.6504C32.822 19.6298 31.0475 26.2524 34.0271 31.4131C35.4704 33.9131 37.8009 35.7012 40.5891 36.4485C41.5202 36.698 42.4649 36.8213 43.4037 36.8213C44.2241 36.8213 45.0398 36.727 45.8376 36.5402L50.5801 44.7566C51.1615 45.7639 52.22 46.3276 53.3073 46.3275C53.8401 46.3275 54.3799 46.192 54.8737 45.9071C55.8434 45.3474 56.4459 44.3037 56.4459 43.1834C56.4459 42.6345 56.3001 42.0918 56.0242 41.6135ZM41.0171 34.8511C38.6555 34.2183 36.6815 32.7037 35.459 30.5863C32.9355 26.2153 34.4385 20.6062 38.8094 18.0825C40.2473 17.2524 41.8187 16.8581 43.3707 16.8581C46.5364 16.8581 49.6198 18.5 51.3132 21.433C52.5356 23.5504 52.8603 26.017 52.2275 28.3787C51.5947 30.7404 50.0801 32.7141 47.9627 33.9366C45.8454 35.1592 43.3788 35.4837 41.0171 34.8511ZM54.0471 44.4748C53.3357 44.8856 52.4229 44.6411 52.0124 43.93L47.4496 36.0249C47.9069 35.8383 48.3547 35.62 48.7897 35.3689C49.2251 35.1175 49.6374 34.8377 50.0278 34.5347L54.592 42.4401C54.7229 42.6673 54.7922 42.9244 54.7922 43.1835C54.7922 43.7147 54.5066 44.2096 54.0471 44.4748Z" fill="currentColor" />
                                <path d="M50.8831 28.339C51.4668 26.4587 51.3278 24.4456 50.4916 22.6706C50.2969 22.2574 49.8043 22.0804 49.3912 22.275C48.978 22.4696 48.801 22.9622 48.9957 23.3753C49.6553 24.7754 49.7648 26.364 49.3038 27.8485C48.8403 29.3413 47.8391 30.594 46.4845 31.3761C45.0511 32.2038 43.381 32.4234 41.7823 31.9951C40.1835 31.5667 38.8471 30.5413 38.0196 29.1079C36.3111 26.1488 37.3287 22.3515 40.2878 20.643C42.4514 19.394 45.1364 19.5609 47.1287 21.0682C47.4929 21.3438 48.0114 21.272 48.2869 20.9078C48.5624 20.5437 48.4906 20.0251 48.1264 19.7496C45.6028 17.84 42.2012 17.6286 39.4608 19.2108C37.6449 20.2592 36.3458 21.9522 35.8031 23.9776C35.2604 26.003 35.539 28.1186 36.5874 29.9346C37.6358 31.7507 39.3288 33.0496 41.3542 33.5925C42.0306 33.7737 42.717 33.8633 43.3988 33.8633C44.759 33.8633 46.1017 33.5066 47.3112 32.8082C49.0274 31.8175 50.2959 30.2304 50.8831 28.339Z" fill="currentColor" />
                            </svg>
                        </div>
                        <h3 class="box-title"><a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a></h3>
                        <p class="box-text">{{ $service->description }}</p>
                        <div class="tg-button-wrap justify-content-center">
                            <a href="{{ route('services.show', $service->slug) }}" class="btn btn-six">
                                <span class="btn-text" data-text="Learn More"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!--======== / Service Section ========-->
<section class="about-area-4 pb-120 overflow-hidden">
        <div class="container">
            <div class="about-wrap4">
                <div class="row gx-80 gy-5 align-items-center">
                    <div class="col-xl-5">
                        <div class="about-thumb4-1">
                            <div class="img1 image-anim">
                                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=500&h=400&fit=crop" alt="Team collaboration">
                            </div>
                            <div class="img2 jump image-anim">
                                <div class="thumb">
                                    <img src="assets/img/others/about4-2.jpg" alt="img">
                                </div>
                            </div>
                            <div class="about-experience-wrap movingX">
                                <h3 class="counter-title"><span class="counter-number">25</span>+</h3>
                                <span class="counter-text">Years of Experience</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="section__title mb-50">
                            <span class="sub-title text-anim">About Our Company</span>
                            <h2 class="title text-anim2">Empowering Businesses Through Cost-Effective Technology Solutions</h2>
                        </div>
                        <ul class="nav nav-tabs about-tabs">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="about-tab1" data-bs-toggle="tab" data-bs-target="#aboutTab1" type="button" role="tab" aria-controls="aboutTab1" aria-selected="true">Our Values</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="about-tab2" data-bs-toggle="tab" data-bs-target="#aboutTab2" type="button" role="tab" aria-controls="aboutTab2" aria-selected="false">Our Vision</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="about-tab3" data-bs-toggle="tab" data-bs-target="#aboutTab3" type="button" role="tab" aria-controls="aboutTab3" aria-selected="false">Our Mission</button>
                            </li>
                        </ul>
                        <div class="tab-content mt-25">
                            <div class="tab-pane fade show active" id="aboutTab1" role="tabpanel" aria-labelledby="about-tab1">
                                <p class="mb-0">We champion <strong>integrity</strong>, <strong>innovation</strong>, and <strong>excellence</strong> in everything we do. Our commitment to continuous improvement and accountability drives us to deliver transformative solutions that exceed expectations and create lasting value for our clients and partners.</p>
                            </div>
                            <div class="tab-pane fade" id="aboutTab2" role="tabpanel" aria-labelledby="about-tab2">
                                <p class="mb-0">To be the leading technology partner for emerging markets, revolutionizing how businesses leverage digital transformation to achieve sustainable growth and competitive advantage in an increasingly connected world.</p>
                            </div>
                            <div class="tab-pane fade" id="aboutTab3" role="tabpanel" aria-labelledby="about-tab3">
                                <p class="mb-0">We deliver cutting-edge technology solutions through strategic partnerships, exceptional talent acquisition, and continuous innovation. Our mission is to empower businesses with scalable, efficient, and cost-effective solutions that drive measurable results.</p>
                            </div>
                        </div>

                        <div class="about-grid-wrap style4 mt-20">
                            <div class="about-grid-card style4">
                                <div class="box-icon">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="card-details">
                                    <h4 class="box-title">Strategic Problem Solving</h4>
                                    <p>We analyze complex business challenges through a strategic lens, developing innovative solutions that align with your long-term objectives and drive sustainable competitive advantage.</p>
                                </div>
                            </div>
                            <div class="about-grid-card style4">
                                <div class="box-icon">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M14.7 6.3A1 1 0 0 0 13 5H5A2 2 0 0 0 3 7V19A2 2 0 0 0 5 21H19A2 2 0 0 0 21 19V11A1 1 0 0 0 19.7 9.3L14.7 6.3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        <path d="M9 13L11 15L16 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="card-details">
                                    <h4 class="box-title">Implementation Excellence</h4>
                                    <p>Our dedicated implementation team ensures seamless deployment of technology solutions with minimal disruption, comprehensive training, and ongoing support for optimal performance.</p>
                                </div>
                            </div>
                        </div>
                         <div class="tg-button-wrap mt-40">
                        <a href="{{ route('contact') }}" class="btn">
                            <span class="btn-text" data-text="Make an Appointment"></span>
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!--======== / About Section ========-->
  <!--==============================
    Faq Area
    ==============================-->
<section class="faq-area-1 overflow-hidden pt-120 pb-120 position-relative z-1">
    <div class="faq-bg-shape1-1"></div>
    <div class="container">
        <div class="row gy-5 gx-80 justify-content-between align-items-center">
            <div class="col-xl-6">
                <div class="faq-thumb1-1">
                    <img src="assets/img/others/faqsearch.jpg" alt="img">
                </div>
            </div>
            <div class="col-xl-6">
                <div class="section__title mb-50">
                    <span class="sub-title text-anim">frequently asked questions</span>
                    <h2 class="title text-anim2">Get every business answer from us</h2>
                </div>
                <div class="accordion-area accordion" id="faqAccordion">

                    <!-- FAQ 1 -->
                    <div class="accordion-card active">
                        <div class="accordion-header" id="collapse-item-1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true" aria-controls="collapse-1">
                                <span class="text-theme">01.</span> What types of technology solutions do you provide?
                            </button>
                        </div>
                        <div id="collapse-1" class="accordion-collapse collapse show" aria-labelledby="collapse-item-1" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="faq-text">
                                    We specialize in delivering technology solutions tailored for emerging markets, including fiscal devices, Operations Support Systems (OSS), and Business Support Systems (BSS). Our services help businesses align their IT investments with their strategic goals, ensuring cost-effective and efficient operations.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="accordion-card">
                        <div class="accordion-header" id="collapse-item-2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">
                                <span class="text-theme">02.</span> Do you provide support for fiscal devices?
                            </button>
                        </div>
                        <div id="collapse-2" class="accordion-collapse collapse" aria-labelledby="collapse-item-2" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="faq-text">
                                    Yes, we have over 25 years of collective experience in supporting fiscal devices. Our team has hands-on experience with devices supplied by First Computers and currently supports over 1,000 clients. We ensure seamless operation and maintenance of these devices to keep your business running smoothly.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="accordion-card">
                        <div class="accordion-header" id="collapse-item-3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">
                                <span class="text-theme">03.</span> Can you help with accounting software integration?
                            </button>
                        </div>
                        <div id="collapse-3" class="accordion-collapse collapse" aria-labelledby="collapse-item-3" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="faq-text">
                                    Absolutely. We provide strategic consulting and implementation support for accounting software integration. Our goal is to align your technology investments with your business processes, ensuring accurate financial management and operational efficiency.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="accordion-card">
                        <div class="accordion-header" id="collapse-item-4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4">
                                <span class="text-theme">04.</span> What industries do you serve?
                            </button>
                        </div>
                        <div id="collapse-4" class="accordion-collapse collapse" aria-labelledby="collapse-item-4" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="faq-text">
                                    We serve a wide range of industries, including mining, retail, and SMEs. Our team has worked with blue-chip clients such as Anglo American Corporation Zimbabwe, Debswana, Orapa Mine, and Jwaneng Mine. We tailor our solutions to meet the unique needs of each industry.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="accordion-card">
                        <div class="accordion-header" id="collapse-item-5">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5">
                                <span class="text-theme">05.</span> Do you offer virtual IT solutions?
                            </button>
                        </div>
                        <div id="collapse-5" class="accordion-collapse collapse" aria-labelledby="collapse-item-5" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p class="faq-text">
                                    Yes, we offer virtual IT solutions to help businesses streamline their operations and reduce costs. Our virtual solutions include remote support, cloud-based systems, and virtual consultations to ensure your business stays connected and efficient, no matter where you are.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
<!--======== / Faq Section ========-->

<!--==============================
Blog Area
==============================-->
<section class="blog-area-2 pb-120">
    <div class="container">
        <div class="section__title mb-50 text-center">
            <span class="sub-title text-anim">Blog Posts</span>
            <h2 class="title text-anim2">Read Our Blog Posts</h2>
        </div>
        <div class="row gy-40 justify-content-center">
            @if($latestBlogs->count() > 0)
             @foreach($latestBlogs as $blog)
                <div class="col-lg-6">
                    <div class="blog__post-item blog__post-item-four">
                        <div class="blog__post-thumb image-anim">
                            <a href="{{ route('blog.details', $blog->slug) }}">
                                <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}">
                            </a>
                        </div>
                        <div class="blog__post-content">
                            <div class="blog__post-meta">
                                <ul class="list-wrap">
                                    <li>
                                        <a href="{{ route('blog.details', $blog->slug) }}">
                                            <i class="fas fa-calendar"></i>
                                            {{ $blog->published_at->format('d M, Y') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('blog.details', $blog->slug) }}">
                                            <i class="fas fa-user"></i>
                                            by {{ $blog->author ?? 'admin' }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <h3 class="title">
                                <a href="{{ route('blog.details', $blog->slug) }}">{{ $blog->title }}</a>
                            </h3>
                            <p class="blog-excerpt">
                                {{ Str::limit($blog->excerpt_or_content, 150) }}
                            </p>
                            <div class="blog__post-bottom">
                                <a href="{{ route('blog.details', $blog->slug) }}" class="link-btn">
                                    READ MORE
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center py-60">
                    <i class="fas fa-newspaper" style="font-size: 60px; color: #ccc; margin-bottom: 20px;"></i>
                    <h3 class="mb-3">No Blog Posts Yet</h3>
                    <p>Check back later for new content.</p>
                </div>
            @endif
        </div>
    </div>
</section>
<!--======== / Blog Section ========-->

<script src="assets/js/swiper-bundle.min.js"></script>


@endsection

