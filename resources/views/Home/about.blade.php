@extends('components.homelayout')
@section('home-content')
<link rel="stylesheet" href="{{ asset('assets/css/extracss.css') }}">

<x-breadcrumb> About Us </x-breadcrumb>

<!--About Area-->
<section class="about-area-2 pt-80 pb-80 overflow-hidden">
    <div class="container">
        <div class="about-wrap2">
            <div class="row gx-60 gy-4 align-items-center">
                <div class="col-xl-6">
                    <div class="about-thumb2-1">
                        <div class="img1">
                            <div class="thumb image-anim">
                                <img src="assets/img/others/about2-1.jpg" alt="Fiscal Support Services team">
                            </div>
                        </div>
                        <div class="img2">
                            <div class="thumb image-anim">
                                <img src="assets/img/others/about1.jpg">
                            </div>
                        </div>
                        <div class="about-bg-shape2-1">
                            <div class="shape1"></div>
                            <div class="shape2"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="section__title">
                        <span class="sub-title text-anim">About Fiscal Support Services</span>
                        <h2 class="title text-anim2">Trusted Technology Solutions Provider Across Africa</h2>
                    </div>
                    <p class="mt-20 mb-30">Fiscal Support Services (Private) Limited is an information technology company focused on delivering cost-effective and efficient technology solutions. Founded by former employees of First Computers, we have been reliably supporting fiscal devices across Africa for the past eight years, with our team bringing over 48 years of collective IT support experience.</p>
                    <div class="checklist-wrap">
                        <ul class="list-wrap">
                            <li>
                                <span class="icon"><i class="fas fa-check-circle"></i></span>
                                Over 25 years' experience in fiscal devices support among our staff
                            </li>
                            <li>
                                <span class="icon"><i class="fas fa-check-circle"></i></span>
                                Supporting more than 1,000 category C clients' fiscal devices
                            </li>
                            <li>
                                <span class="icon"><i class="fas fa-check-circle"></i></span>
                                Extensive experience across Botswana, Zimbabwe, Namibia, Kenya and South Africa
                            </li>
                        </ul>
                    </div>
                    <div class="tg-button-wrap mt-25">
                        <a href="{{ route('contact') }}" class="btn">
                            <span class="btn-text" data-text="Contact Our Experts"></span>
                        </a>
                        <a href="{{ route('services.index') }}" class="btn btn-six">
                            <span class="btn-text" data-text="Our Services"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Counter Area -->
<section class="section-spacing pt-60 pb-60">
    <div class="container">
        <div class="row gy-3 justify-content-center">
            <div class="col-lg-4 col-md-6" data-aos="fade-up">
                <div class="stats-card">
                    <h3 class="stats-card_number"><span class="counter-number" data-count="48">48</span>+</h3>
                    <p class="stats-card_title">Years Combined Experience</p>
                    <p class="stats-card_text">Our team brings decades of hands-on IT support experience across multiple African markets.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card">
                    <h3 class="stats-card_number"><span class="counter-number" data-count="1000">1000</span>+</h3>
                    <p class="stats-card_title">Clients Supported</p>
                    <p class="stats-card_text">We currently support over 1,000 category C clients with their fiscal device needs.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card">
                    <h3 class="stats-card_number"><span class="counter-number" data-count="5">5</span></h3>
                    <p class="stats-card_title">African Countries</p>
                    <p class="stats-card_text">We operate in Botswana, Zimbabwe, Namibia, Kenya and South Africa.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Company Overview -->
<section class="company-profile pt-60 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-container center" data-aos="fade-up">
                    <span class="section-tag">Who We Are</span>
                    <h2 class="main-heading">Company Overview</h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">
                <div class="profile-content">
                    <p>Fiscal Support Services (Private) Limited has been reliably supporting fiscal devices supplied by First Computers for the past eight years since First Computers closed down and handed over its business to us. We have gained valuable experience in this industry with collectively more than 25 years' experience in fiscal devices support amongst our staff members.</p>
                    <p>The scarcity of Information Technology (I.T) services which are right-sized for the emerging markets of Africa resulted in the incorporation of our technologically driven enterprise. We help businesses acquire hardware through financed models and deploy solutions that align IT investment cycles to business strategy.</p>
                    <p>The growing number of SMEs in Zimbabwe and the region that experience operating complexity due to the misalignment of technology investments and business processes require strategic technology consulting services. These services enable them to grow while maintaining control over their organization's operations. Fiscal Support Services (Private) Limited has come to fill this gap in the market.</p>
                    <p>More than 75% of our staff members have had hands-on experience supporting fiscal devices since the launch of the project in Zimbabwe. This deep expertise allows us to provide unmatched service quality to our growing client base.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Area -->
<section class="mission-values-section pt-60 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-container center" data-aos="fade-up">
                    <span class="section-tag">Our Core Principles</span>
                    <h2 class="main-heading">Mission & Values That Drive Us</h2>
                </div>
            </div>
        </div>
        <div class="row gy-3">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="mission-container">
                    <h3 class="mission-container__title">Our Mission</h3>
                    <ul class="mission-list">
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Develop differentiated services covering OSS (Operations Support Systems) and BSS (Business Support Systems)</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Recruit, hire and retain the best technology skills in the market</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Refine our value chain to create new value points for our customers</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Ensure our employees and partners uphold our values in all actions</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="values-container">
                    <h3 class="values-container__title">Our Core Values</h3>
                    <div class="value-item">
                        <div class="value-icon-container">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="value-content">
                            <h4>Integrity</h4>
                            <p>We consistently align our actions, methods, measures, principles, expectations and outcomes to our values.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon-container">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="value-content">
                            <h4>Passion</h4>
                            <p>We show unusual excitement and enthusiasm for our people, customers, partners and our technology.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon-container">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="value-content">
                            <h4>Continual Improvement</h4>
                            <p>We're committed to building our people and technology through continuous learning and self-improvement.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Extended Values Section -->
<section class="extended-values pt-60 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-container center" data-aos="fade-up">
                    <span class="section-tag">What Guides Us</span>
                    <h2 class="main-heading">Our Extended Values</h2>
                </div>
            </div>
        </div>
        <div class="row gy-3">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="value-feature-card">
                    <div class="value-icon-box">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <h4 class="value-feature-title">Taking on Big Challenges</h4>
                    <p class="value-feature-text">We take on complex business and technology projects that challenge and stimulate our capacity to deliver, and we see them through. Heroes happen at Fiscal Support Services.</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="value-feature-card">
                    <div class="value-icon-box">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h4 class="value-feature-title">Being Accountable</h4>
                    <p class="value-feature-text">We hold ourselves accountable to our customers, shareholders, partners, and employees by honoring our commitments, providing results, and striving for the highest quality.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Client Experience Area -->
<section class="experience-section pt-60 pb-60">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-container center" data-aos="fade-up">
                    <span class="section-tag">Our Expertise</span>
                    <h2 class="main-heading">African Market Experience</h2>
                </div>
            </div>
        </div>
        <div class="row gy-3">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="client-section">
                    <h3 class="client-section__title">Regional Presence</h3>
                    <p>Our staff have hands-on experience in multiple African markets including:</p>
                    <ul class="country-list">
                        <li><i class="fas fa-map-marker-alt"></i> Botswana</li>
                        <li><i class="fas fa-map-marker-alt"></i> Zimbabwe</li>
                        <li><i class="fas fa-map-marker-alt"></i> Namibia</li>
                        <li><i class="fas fa-map-marker-alt"></i> Kenya</li>
                        <li><i class="fas fa-map-marker-alt"></i> South Africa</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="client-section">
                    <h3 class="client-section__title">Notable Clients</h3>
                    <p>We've worked with leading organizations across Zimbabwe:</p>
                    <div class="company-card-container">
                        <div class="company-card">
                            <h5>Zimgold Oil Industries</h5>
                            <p>Leading petroleum products manufacturer and distributor</p>
                            <span class="industry">Energy Sector</span>
                        </div>
                        <div class="company-card">
                            <h5>Zesa Holdings</h5>
                            <p>National electricity generation and distribution company</p>
                            <span class="industry">Utilities</span>
                        </div>
                        <div class="company-card">
                            <h5>First Mutual Holdings</h5>
                            <p>Diversified financial services group</p>
                            <span class="industry">Financial Services</span>
                        </div>
                        <div class="company-card">
                            <h5>Auto Control</h5>
                            <p>Automotive parts and accessories distributor</p>
                            <span class="industry">Automotive</span>
                        </div>
                        <div class="company-card">
                            <h5>Innscor Africa</h5>
                            <p>Diversified industrial conglomerate</p>
                            <span class="industry">Manufacturing</span>
                        </div>
                        <div class="company-card">
                            <h5>CBZ Holdings</h5>
                            <p>Leading banking and financial services group</p>
                            <span class="industry">Banking</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    
<!--CTA Area-->
<section class="cta-area pt-60 pb-60" style="background-image: url('assets/img/bg/cta-bg.jpg');">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="cta-content text-center">
                    <h2 class="cta-title">Ready to Transform Your Business Technology?</h2>
                    <p class="cta-text">Our team of experts is ready to help you align your IT investments with your business strategy for optimal growth.</p>
                    <div class="cta-button mt-30">
                        <a href="{{ route('contact') }}" class="btn btn-white">
                            <span class="btn-text">Get In Touch</span>
                        </a>
                        <a href="{{ route('services.index') }}" class="btn btn-border-white">
                            <span class="btn-text">Our Services</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    // Initialize AOS animation
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            duration: 800,
            once: true,
            offset: 80
        });
        
        // Counter animation
        const counters = document.querySelectorAll('.counter-number');
        
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const duration = 2000;
            const step = Math.ceil(target / (duration / 16));
            
            let current = 0;
            const updateCounter = () => {
                current += step;
                if (current > target) current = target;
                counter.textContent = current;
                
                if (current < target) {
                    requestAnimationFrame(updateCounter);
                }
            };
            
            // Start counter when element is in view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(counter);
        });
    });
</script>
@endsection