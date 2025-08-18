@extends('components.homelayout')
@section('home-content')

<!-- Breadcrumb -->
<x-breadcrumb> Services </x-breadcrumb>

<style>
/* Improved color variables and design system */
:root {
    --primary: #0c6215;
    --primary-light: #077c2c;
    --primary-dark: #099d4c;
    --secondary: #64748b;
    --accent: #f59e0b;
    --text: #374151;
    --text-light: #6b7280;
    --bg-light: #f8fafc;
    --bg-dark: #1e293b;
    --white: #ffffff;
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
    --radius-sm: 0.25rem;
    --radius-md: 0.5rem;
    --radius-lg: 1rem;
    --transition: all 0.3s ease;
}

/* Enhanced Hero Section */
.hero-section {
    padding: 6rem 0 3rem;
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    color: var(--white);
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('/api/placeholder/1920/800') center/cover no-repeat;
    opacity: 0.15;
    z-index: 0;
}

.hero-section .container {
    position: relative;
    z-index: 1;
}

.hero-section h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    line-height: 1.2;
    text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.hero-section p {
    font-size: 1.25rem;
    max-width: 700px;
    margin-bottom: 2rem;
    opacity: 0.9;
}

/* Improved Services Grid */
.services-section {
    padding: 5rem 0;
    background-color: var(--bg-light);
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
}

.section-title h2::after {
    content: '';
    position: absolute;
    width: 80px;
    height: 4px;
    background: var(--primary);
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 2px;
}

.section-title p {
    font-size: 1.125rem;
    color: var(--text-light);
    max-width: 700px;
    margin: 0 auto;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
    gap: 2rem;
}

.service-card {
    background-color: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.service-image {
    height: 180px;
    overflow: hidden;
    position: relative;
}

.service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--transition);
}

.service-card:hover .service-image img {
    transform: scale(1.05);
}

.service-content {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.service-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--text);
    position: relative;
    padding-bottom: 1rem;
}

.service-content h3::after {
    content: '';
    position: absolute;
    width: 50px;
    height: 3px;
    background: var(--primary);
    bottom: 0;
    left: 0;
    border-radius: 2px;
}

.service-content p {
    color: var(--text-light);
    margin-bottom: 1.5rem;
    line-height: 1.6;
    flex-grow: 1;
}

.learn-more {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background-color: var(--primary);
    color: var(--white);
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    margin-top: auto;
    width: fit-content;
}

.learn-more:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
}

.learn-more svg {
    margin-left: 0.5rem;
    transition: var(--transition);
}

.learn-more:hover svg {
    transform: translateX(3px);
}

/* Stats Section */
.stats-section {
    padding: 4rem 0;
    background-color: var(--primary);
    color: var(--white);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    text-align: center;
}

.stat-item {
    padding: 1.5rem;
    border-radius: var(--radius-md);
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    line-height: 1;
}

.stat-label {
    font-size: 1.125rem;
    opacity: 0.9;
}

/* About Section Improvements */
.about-area-1 {
    padding: 5rem 0;
    overflow: hidden;
}

.about-thumb1-1 {
    position: relative;
}

.about-thumb1-1 .img1 {
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.about-thumb1-1 .img1 img {
    width: 100%;
    height: auto;
    transition: var(--transition);
}

.about-thumb1-1:hover .img1 img {
    transform: scale(1.05);
}

.about-experience-wrap {
    position: absolute;
    bottom: 2rem;
    right: 2rem;
    background-color: var(--primary);
    color: var(--white);
    padding: 1.5rem;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-lg);
    text-align: center;
    min-width: 150px;
}

.counter-title {
    font-size: 3rem;
    font-weight: 800;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.counter-text {
    font-size: 1.125rem;
    font-weight: 600;
}

.about-grid-card {
    margin-top: 2rem;
}

.box-icon {
    width: 60px;
    height: 60px;
    background-color: var(--primary-light);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 1rem;
}

.box-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: var(--text);
}

.box-text {
    color: var(--text-light);
    line-height: 1.6;
}

.tg-button-wrap {
    margin-top: 2.5rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    padding: 1rem 2rem;
    background-color: var(--primary);
    color: var(--white);
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    overflow: hidden;
    position: relative;
}

.btn:hover {
    background-color: var(--primary-dark);
}

.btn-text {
    position: relative;
    z-index: 1;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .hero-section h1 {
        font-size: 2.5rem;
    }
    
    .section-title h2 {
        font-size: 2rem;
    }
    
    .services-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .hero-section {
        padding: 4rem 0 2rem;
    }
    
    .hero-section h1 {
        font-size: 2rem;
    }
    
    .hero-section p {
        font-size: 1.125rem;
    }
    
    .section-title h2 {
        font-size: 1.75rem;
    }
    
    .services-section,
    .about-area-1 {
        padding: 3rem 0;
    }
    
    .counter-title {
        font-size: 2.5rem;
    }
}

@media (max-width: 576px) {
    .services-grid {
        grid-template-columns: 1fr;
    }
    
    .about-experience-wrap {
        position: static;
        margin-top: 1.5rem;
        width: 100%;
    }
}
</style>

<!-- Services Section -->
<section class="services-section">
    <div class="container">
        <div class="services-grid">
            @foreach($services as $service)
                <div class="service-card">
                    <div class="service-image">
                        <img src="{{ $service->image ? asset('storage/' . $service->image) : '/api/placeholder/600/400' }}" alt="{{ $service->title }}">
                    </div>
                    <div class="service-content">
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->description }}</p>
                        <a href="{{ route('services.show', $service->slug) }}" class="learn-more">
                            Learn More
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!--==============================
Company Overview Area
==============================-->
<section class="about-area-1 pb-120 overflow-hidden">
    <div class="container">
        <div class="about-wrap1">
            <div class="row gx-100 gy-5 flex-row-reverse align-items-center">
                <div class="col-xl-6">
                    <div class="about-thumb1-1">
                        <div class="img1">
                            <div class="thumb image-anim">
                                <img src="assets/img/others/about1-1.jpg" alt="Fiscal Support Services Team">
                            </div>
                        </div>
                        <div class="about-experience-wrap movingX">
                            <h3 class="counter-title"><span class="counter-number">80</span>+</h3>
                            <span class="counter-text">Years Combined Experience</span>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="section__title">
                        <span class="sub-title text-anim">About Fiscal Support Services</span>
                        <h2 class="title text-anim2">Trusted Technology Solutions Provider Across Africa</h2>
                    </div>
                    <p class="mt-30 mb-30">Fiscal Support Services (Private) Limited is an information technology company focused on delivering cost-effective and efficient technology solutions. We have been reliably supporting fiscal devices across Africa for the past eight years, with our team bringing over 80 years of collective IT support experience.</p>
                    
                    <div class="about-grid-card">
                        <div class="box-icon">
                            <svg width="50" height="51" viewBox="0 0 50 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.22667 44.6789C9.43281 44.8467 9.68092 44.9284 9.92742 44.9284C10.2501 44.9284 10.5702 44.7885 10.7896 44.5188C11.177 44.043 11.1053 43.3432 10.6295 42.9559C7.34746 40.284 4.92538 36.8233 3.54499 32.9554C7.7237 33.801 12.0196 34.3944 16.3902 34.7305C16.5938 36.7366 16.863 38.6652 17.1945 40.4797C17.3049 41.0832 17.8834 41.4827 18.487 41.3729C19.0906 41.2626 19.4905 40.6839 19.3802 40.0804C19.0797 38.4357 18.8319 36.6927 18.6395 34.8805C20.4476 34.9825 22.2679 35.041 24.0965 35.0554C24.0995 35.0554 24.1026 35.0554 24.1055 35.0554C24.715 35.0554 25.2114 34.5638 25.2162 33.9532C25.221 33.3396 24.7276 32.8384 24.1142 32.8335C22.2093 32.8185 20.3141 32.7548 18.4327 32.6428C18.2447 30.2635 18.1481 27.7899 18.1481 25.2819C18.1481 23.2067 18.2132 21.1699 18.3388 19.2023C20.5277 19.3489 22.7494 19.4224 25.0001 19.4224C27.2397 19.4224 29.4628 19.3478 31.6592 19.1997C31.7628 20.8067 31.8259 22.4579 31.8455 24.1353C31.8527 24.7445 32.3485 25.2333 32.9561 25.2333C32.9604 25.2333 32.9648 25.2333 32.9693 25.2333C33.5828 25.2261 34.0743 24.723 34.0671 24.1095C34.047 22.3835 33.9813 20.6828 33.8731 19.0247C36.344 18.8001 38.7762 18.4812 41.1554 18.0688C41.7598 17.964 42.1651 17.389 42.0603 16.7844C41.9555 16.1799 41.3803 15.7743 40.7759 15.8795C38.4649 16.28 36.1021 16.5904 33.7009 16.8092C33.2273 11.5691 32.3202 6.86747 31.0819 3.3205C35.7277 4.59771 39.8746 7.33356 42.9388 11.2434C43.3174 11.7264 44.0155 11.811 44.4985 11.4327C44.9814 11.0542 45.0662 10.3559 44.6876 9.87303C39.9106 3.77796 32.7349 0.282227 24.9997 0.282227C23.4894 0.282227 22.0091 0.41702 20.5719 0.67488C20.5387 0.679447 20.5056 0.685372 20.473 0.692901C12.2578 2.2018 5.4337 7.73967 2.13447 15.1755C2.10016 15.237 2.07115 15.3025 2.04856 15.3715C0.730994 18.4115 0 21.7633 0 25.2819C0 32.8355 3.36304 39.9055 9.22667 44.6789ZM21.5004 2.77244C22.6414 2.59581 23.8099 2.50409 24.9997 2.50409C26.1826 2.50409 27.3514 2.59383 28.4976 2.76948C29.9275 6.22646 30.9717 11.254 31.488 16.9842C29.3484 17.1282 27.1824 17.2004 24.9998 17.2004C22.8062 17.2004 20.6403 17.1289 18.508 16.9867C19.0202 11.2596 20.0595 6.25238 21.5004 2.77244ZM18.9133 3.32976C17.6689 6.88771 16.7661 11.5741 16.2959 16.8127C12.293 16.4491 8.41556 15.8311 4.69641 14.9623C7.56866 9.33472 12.7141 5.05072 18.9133 3.32976ZM2.22187 25.2819C2.22187 22.372 2.77067 19.587 3.76977 17.0265C7.72666 17.9732 11.8578 18.642 16.1252 19.0281C15.994 21.0558 15.926 23.1508 15.926 25.2819C15.926 27.7309 16.0161 30.1502 16.1921 32.4862C11.6355 32.1201 7.16564 31.4685 2.83078 30.5366C2.42936 28.8343 2.22187 27.0731 2.22187 25.2819ZM41.1135 2.81824C42.9438 2.40522 45.4064 2.19427 46.7471 3.53479C48.314 5.1017 48.0787 8.6262 46.1016 13.2047C44.0756 17.8959 40.4025 23.3369 35.7069 28.6169C34.8637 27.998 33.8237 27.6322 32.7001 27.6322C29.8921 27.6322 27.6077 29.9166 27.6077 32.7246C27.6077 35.5325 29.8921 37.8169 32.7001 37.8169C35.508 37.8169 37.7925 35.5325 37.7925 32.7246C37.7925 31.849 37.5703 31.0242 37.1793 30.3038C42.1185 24.7851 45.9972 19.0508 48.1414 14.0856C50.5681 8.46622 50.6292 4.27443 48.3183 1.96356C47.1837 0.828806 44.9276 -0.320022 40.6248 0.650809C40.0263 0.785849 39.6506 1.38057 39.7856 1.97899C39.9206 2.57741 40.5152 2.95353 41.1135 2.81824ZM32.6999 35.5951C31.1171 35.5951 29.8294 34.3074 29.8294 32.7246C29.8294 31.1417 31.1171 29.854 32.6999 29.854C34.2828 29.854 35.5705 31.1417 35.5705 32.7246C35.5705 34.3074 34.2828 35.5951 32.6999 35.5951ZM27.501 39.6318C22.2684 43.9637 16.9242 47.2802 12.4527 48.9707C10.1386 49.8455 8.09055 50.2822 6.35083 50.2822C4.37819 50.2822 2.8019 49.721 1.68134 48.6004C0.551887 47.4709 -0.594472 45.2272 0.358338 40.9526C0.491896 40.3538 1.08575 39.9765 1.68442 40.11C2.28334 40.2435 2.66056 40.8372 2.52713 41.436C2.12238 43.2519 1.91969 45.6963 3.25256 47.0293C4.6769 48.4538 7.66531 48.4051 11.667 46.8923C15.9153 45.2864 21.0354 42.1 26.0841 37.9203C26.5566 37.5291 27.257 37.595 27.6483 38.0675C28.0396 38.5402 27.9737 39.2405 27.501 39.6318ZM49.4996 20.2848C49.8314 21.9209 49.9998 23.6022 49.9998 25.2819C49.9998 27.5023 49.7087 29.6565 49.1628 31.7074C49.1549 31.7448 49.145 31.7816 49.1334 31.8178C46.6386 41.0187 39.0088 48.1305 29.5249 49.8716C29.4933 49.8787 29.4614 49.8845 29.4294 49.889C27.9914 50.1471 26.5111 50.2819 24.9999 50.2819C23.1799 50.2819 21.3626 50.085 19.5988 49.6966C18.9997 49.5647 18.6208 48.9719 18.7528 48.3728C18.8846 47.7735 19.4777 47.3947 20.0767 47.5267C21.6839 47.8806 23.3403 48.06 25.0001 48.06C26.1899 48.06 27.358 47.9684 28.4989 47.7918C29.2499 45.9707 29.9193 43.6355 30.4462 40.9911C30.5662 40.3895 31.1512 39.9989 31.7528 40.1188C32.3546 40.2387 32.7451 40.8236 32.6251 41.4254C32.1898 43.6102 31.6747 45.5548 31.087 47.2344C38.2255 45.2527 43.9652 39.8743 46.4477 32.9569C44.7273 33.3047 42.9791 33.6114 41.2294 33.8724C40.6221 33.9626 40.0574 33.5443 39.9668 32.9374C39.8763 32.3305 40.2949 31.7652 40.9017 31.6747C43.0085 31.3606 45.1125 30.9785 47.1661 30.5375C47.5663 28.8497 47.778 27.0905 47.778 25.2822C47.778 23.7503 47.6247 22.2177 47.3223 20.7267C47.2003 20.1255 47.5889 19.5391 48.1902 19.4172C48.7909 19.2951 49.3776 19.6834 49.4996 20.2848Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="card-details">
                            <h4 class="box-title">African Market Expertise</h4>
                            <p class="box-text">Extensive experience across Botswana, Zimbabwe, Namibia, Kenya and South Africa with over 1,000 category C clients supported.</p>
                        </div>
                    </div>
                    
                    <div class="about-grid-card">
                        <div class="box-icon">
                            <svg width="50" height="51" viewBox="0 0 50 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M49.7139 9.45371L40.8271 0.568262C40.644 0.385156 40.3957 0.282227 40.1367 0.282227H13.8672C13.3278 0.282227 12.8906 0.719531 12.8906 1.25879V5.55566H6.24873C5.70937 5.55566 5.27217 5.99297 5.27217 6.53223C5.27217 7.07148 5.70937 7.50879 6.24873 7.50879H25.3406C25.88 7.50879 26.3172 7.07148 26.3172 6.53223C26.3172 5.99297 25.88 5.55566 25.3406 5.55566H14.8438V2.23535H39.1602V10.1455C39.1602 10.6848 39.5974 11.1221 40.1367 11.1221H48.0469V48.3291H21.4844V45.5947H23.4375C23.9769 45.5947 24.4141 45.1574 24.4141 44.6182V39.9307C24.4141 39.3914 23.9769 38.9541 23.4375 38.9541H21.4844V35.2432C21.4844 34.7039 21.0472 34.2666 20.5078 34.2666H14.3065C16.3006 32.654 17.5781 30.189 17.5781 27.4307C17.5781 22.5844 13.6354 18.6416 8.78906 18.6416C3.94277 18.6416 0 22.5844 0 27.4307C0 30.189 1.27754 32.654 3.27158 34.2666H0.976562C0.437207 34.2666 0 34.7039 0 35.2432V39.9307C0 40.4699 0.437207 40.9072 0.976562 40.9072H2.92969V43.6416H0.976562C0.437207 43.6416 0 44.0789 0 44.6182V49.3057C0 49.8449 0.437207 50.2822 0.976562 50.2822H49.0234C49.5628 50.2822 50 49.8449 50 49.3057V10.1442C50 9.88525 49.8971 9.63682 49.7139 9.45371Z" fill="currentColor"/>
                            </svg>
                        </div>
                        <div class="card-details">
                            <h4 class="box-title">Fiscal Device Support</h4>
                            <p class="box-text">More than 75% of our staff have hands-on experience supporting fiscal devices since the project launch in Zimbabwe, ensuring unmatched service quality.</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>


@endsection