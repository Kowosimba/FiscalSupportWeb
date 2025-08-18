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
                        <h2 class="title text-anim2">Leading Technology Solutions Provider Across Africa</h2>
                    </div>
                    <p class="mt-20 mb-30">Fiscal Support Services (Private) Limited is a specialized information technology company focused on delivering cost-effective and efficient technology solutions. We have been reliably supporting fiscal devices and providing comprehensive IT services across Africa for over eight years, with our team bringing more than 80 years of collective IT support experience to every project.</p>
                    <div class="checklist-wrap">
                        <ul class="list-wrap">
                            <li>
                                <span class="icon"><i class="fas fa-check-circle"></i></span>
                                Over 80 years' combined experience in fiscal device support and IT solutions
                            </li>
                            <li>
                                <span class="icon"><i class="fas fa-check-circle"></i></span>
                                Successfully supporting more than 1,000 category C clients with their fiscal devices
                            </li>
                            <li>
                                <span class="icon"><i class="fas fa-check-circle"></i></span>
                                Proven track record across Botswana, Zimbabwe, Namibia, Kenya and South Africa
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
                    <h3 class="stats-card_number"><span class="counter-number" data-count="80">80+</span>+</h3>
                    <p class="stats-card_title">Years Combined Experience</p>
                    <p class="stats-card_text">Our team brings decades of hands-on IT support experience across multiple African markets.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="stats-card">
                    <h3 class="stats-card_number"><span class="counter-number" data-count="1000">1000</span>+</h3>
                    <p class="stats-card_title">Clients Supported</p>
                    <p class="stats-card_text">We currently support over 1,000 category C clients with their fiscal device needs and IT requirements.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="stats-card">
                    <h3 class="stats-card_number"><span class="counter-number" data-count="5">5</span></h3>
                    <p class="stats-card_title">African Countries</p>
                    <p class="stats-card_text">We operate across Botswana, Zimbabwe, Namibia, Kenya and South Africa.</p>
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
                    <p>Fiscal Support Services (Private) Limited has established itself as a leading provider of fiscal device support and comprehensive IT solutions across Africa. Over the past eight years, we have built an unparalleled reputation for reliability, expertise, and innovative technology solutions that drive business growth.</p>
                    
                    <p>Our team collectively brings more than 25 years of specialized experience in fiscal device support, making us the go-to partner for businesses seeking robust, compliant, and efficient fiscalization solutions. We understand the unique challenges facing African businesses and have developed tailored approaches to address them effectively.</p>
                    
                    <p>The scarcity of Information Technology (I.T) services that are properly scaled for the emerging markets of Africa led to the incorporation of our technologically driven enterprise. We help businesses acquire hardware through innovative financing models and deploy solutions that strategically align IT investment cycles with business objectives and growth plans.</p>
                    
                    <p>The growing number of SMEs in Zimbabwe and the broader African region often experience operational complexity due to misaligned technology investments and business processes. These organizations require strategic technology consulting services that enable sustainable growth while maintaining operational control. Fiscal Support Services (Private) Limited was founded specifically to address this critical gap in the market.</p>
                    
                    <p>More than 75% of our staff members have extensive hands-on experience in fiscal device support and implementation since the early days of fiscalization projects across Africa. This deep, specialized expertise allows us to provide unmatched service quality, rapid problem resolution, and innovative solutions to our expanding client base throughout the continent.</p>
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
                            <span>Develop innovative services covering OSS (Operations Support Systems) and BSS (Business Support Systems) for African markets</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Recruit, develop and retain the most skilled technology professionals in the industry</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Continuously refine our value chain to create exceptional value for our customers</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Ensure all team members and partners consistently uphold our core values in every interaction</span>
                        </li>
                        <li>
                            <i class="fas fa-check"></i>
                            <span>Bridge the technology gap for emerging markets through strategic consulting and implementation</span>
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
                            <p>We consistently align our actions, methods, principles, and outcomes with our values, maintaining transparency in all business dealings.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon-container">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="value-content">
                            <h4>Passion</h4>
                            <p>We demonstrate exceptional enthusiasm and dedication to our people, customers, partners, and cutting-edge technology solutions.</p>
                        </div>
                    </div>
                    <div class="value-item">
                        <div class="value-icon-container">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="value-content">
                            <h4>Continuous Excellence</h4>
                            <p>We're committed to developing our people and technology through continuous learning, innovation, and strategic improvement.</p>
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
                    <span class="section-tag">What Drives Excellence</span>
                    <h2 class="main-heading">Our Leadership Principles</h2>
                </div>
            </div>
        </div>
        <div class="row gy-3">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="value-feature-card">
                    <div class="value-icon-box">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <h4 class="value-feature-title">Embracing Complex Challenges</h4>
                    <p class="value-feature-text">We actively pursue complex business and technology projects that push the boundaries of innovation and challenge our capacity to deliver exceptional results. Every team member has the opportunity to become a technology hero at Fiscal Support Services.</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="value-feature-card">
                    <div class="value-icon-box">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h4 class="value-feature-title">Unwavering Accountability</h4>
                    <p class="value-feature-text">We hold ourselves to the highest standards of accountability to our customers, stakeholders, partners, and employees by consistently honoring commitments, delivering measurable results, and maintaining exceptional quality standards.</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                <div class="value-feature-card">
                    <div class="value-icon-box">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h4 class="value-feature-title">Innovation Leadership</h4>
                    <p class="value-feature-text">We lead the market through innovative thinking, cutting-edge solutions, and forward-thinking approaches that anticipate and address future business needs across African markets.</p>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                <div class="value-feature-card">
                    <div class="value-icon-box">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="value-feature-title">Partnership Excellence</h4>
                    <p class="value-feature-text">We build lasting partnerships with our clients by deeply understanding their business objectives and delivering technology solutions that drive sustainable growth and competitive advantage.</p>
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
                    <h2 class="main-heading">Pan-African Market Leadership</h2>
                </div>
            </div>
        </div>
        <div class="row gy-3">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="client-section">
                    <h3 class="client-section__title">Regional Excellence</h3>
                    <p>Our experienced team has successfully delivered technology solutions across multiple African markets, with deep understanding of local business environments and regulatory requirements:</p>
                    <ul class="country-list">
                        <li><i class="fas fa-map-marker-alt"></i> <strong>Zimbabwe</strong> - Headquarters and primary market focus</li>
                        <li><i class="fas fa-map-marker-alt"></i> <strong>Botswana</strong> - Expanding fiscal device support services</li>
                        <li><i class="fas fa-map-marker-alt"></i> <strong>Namibia</strong> - Strategic technology consulting</li>
                        <li><i class="fas fa-map-marker-alt"></i> <strong>Kenya</strong> - IT infrastructure and support</li>
                        <li><i class="fas fa-map-marker-alt"></i> <strong>South Africa</strong> - Regional partnership development</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="client-section">
                    <h3 class="client-section__title">Trusted by Industry Leaders</h3>
                    <p>We've earned the trust of leading organizations across Zimbabwe's key economic sectors:</p>
                    <div class="company-card-container">
                        <div class="company-card">
                            <h5>Zimgold Oil Industries</h5>
                            <p>Leading petroleum products manufacturer and distributor</p>
                            <span class="industry">Energy & Petroleum</span>
                        </div>
                        <div class="company-card">
                            <h5>ZESA Holdings</h5>
                            <p>National electricity generation and distribution company</p>
                            <span class="industry">Power & Utilities</span>
                        </div>
                        <div class="company-card">
                            <h5>First Mutual Holdings</h5>
                            <p>Diversified financial services and insurance group</p>
                            <span class="industry">Financial Services</span>
                        </div>
                        <div class="company-card">
                            <h5>Auto Control</h5>
                            <p>Automotive parts and accessories distributor</p>
                            <span class="industry">Automotive Retail</span>
                        </div>
                        <div class="company-card">
                            <h5>Innscor Africa</h5>
                            <p>Diversified industrial and manufacturing conglomerate</p>
                            <span class="industry">Manufacturing</span>
                        </div>
                        <div class="company-card">
                            <h5>CBZ Holdings</h5>
                            <p>Leading banking and financial services group</p>
                            <span class="industry">Banking & Finance</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Approach Section -->
<section class="approach-section pt-60 pb-60" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="heading-container center" data-aos="fade-up">
                    <span class="section-tag">Our Methodology</span>
                    <h2 class="main-heading">Strategic Technology Approach</h2>
                </div>
            </div>
        </div>
        <div class="row gy-4">
            <div class="col-lg-4" data-aos="fade-up">
                <div class="approach-card">
                    <div class="approach-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>Analysis & Assessment</h4>
                    <p>We begin with comprehensive analysis of your business processes, technology infrastructure, and growth objectives to identify optimization opportunities.</p>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="approach-card">
                    <div class="approach-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h4>Strategic Planning</h4>
                    <p>We develop customized technology roadmaps that align IT investments with your business strategy, ensuring sustainable growth and operational efficiency.</p>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="approach-card">
                    <div class="approach-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h4>Implementation & Support</h4>
                    <p>We execute solutions with precision and provide ongoing support to ensure your technology investments continue delivering value as your business grows.</p>
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
                    <p class="cta-text">Partner with Africa's leading fiscal device and IT solutions provider. Our team of experts is ready to help you align your technology investments with your business strategy for optimal growth and competitive advantage.</p>
                    <div class="cta-button mt-30">
                        <a href="{{ route('contact') }}" class="btn btn-white">
                            <span class="btn-text">Get In Touch</span>
                        </a>
                        <a href="{{ route('services.index') }}" class="btn btn-border-white">
                            <span class="btn-text">Explore Our Services</span>
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