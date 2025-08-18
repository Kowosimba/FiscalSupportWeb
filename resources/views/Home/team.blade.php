@extends('components.homelayout')
@section('home-content')

<x-breadcrumb> Our Team </x-breadcrumb>

<!-- Team Leadership Area -->
<section class="team-page-area pt-120 pb-120 overflow-hidden">
    <div class="container">
        <div class="row justify-content-center mb-60">
            <div class="col-xl-8 text-center">
                <div class="section__title">
                    <h1 class="title text-anim2">Experienced Professionals Driving Innovation</h1>
                    <p class="section-desc">Our leadership team brings decades of combined experience in IT solutions, fiscal device support, and African market expertise to serve your business needs.</p>
                </div>
            </div>
        </div>
        
        <div class="row gy-40">
            <!-- Director Finance & Admin - Tapiwa Gandiwa -->
            <div class="col-xl-4 col-md-6">
                <div class="team-card-modern">
                    <div class="team-card-thumb">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="team-card-overlay">
                            <div class="social-links">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="mailto:tapiwa@company.com"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-card-content">
                        <h4 class="team-name">Tapiwa Gandiwa</h4>
                        <span class="team-designation">Director Finance & Admin</span>
                        <p class="team-bio">Leading financial strategy and administrative operations with expertise in corporate finance and regulatory compliance.</p>
                        <div class="expertise-tags">
                            <span class="tag">Financial Planning</span>
                            <span class="tag">Compliance</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Director Technical - Daniel Chanda -->
            <div class="col-xl-4 col-md-6">
                <div class="team-card-modern">
                    <div class="team-card-thumb">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="team-card-overlay">
                            <div class="social-links">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="mailto:daniel@company.com"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-card-content">
                        <h4 class="team-name">Daniel Chanda</h4>
                        <span class="team-designation">Director Technical</span>
                        <p class="team-bio">Driving technical excellence with expertise in fiscal compliance systems and enterprise IT solutions.</p>
                        <div class="expertise-tags">
                            <span class="tag">Fiscal Systems</span>
                            <span class="tag">IT Solutions</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Director Network Infrastructure & Security - Paul Musandu -->
            <div class="col-xl-4 col-md-6">
                <div class="team-card-modern">
                    <div class="team-card-thumb">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="team-card-overlay">
                            <div class="social-links">
                                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a href="mailto:paul@company.com"><i class="fas fa-envelope"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="team-card-content">
                        <h4 class="team-name">Paul Musandu</h4>
                        <span class="team-designation">Director Network Infrastructure & Security</span>
                        <p class="team-bio">Ensuring robust network architecture and cybersecurity with expertise in enterprise infrastructure and data protection.</p>
                        <div class="expertise-tags">
                            <span class="tag">Network Infrastructure</span>
                            <span class="tag">Cybersecurity</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Modern Team Card Styles */
.team-card-modern {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    height: 450px; /* Fixed height for uniformity */
}

.team-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.team-card-thumb {
    position: relative;
    overflow: hidden;
    height: 320px; /* Increased height for photo space */
    background: linear-gradient(135deg, var(--theme-color) 0%, var(--theme-color2) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar {
    width: 120px;
    height: 120px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid rgba(255,255,255,0.3);
    transition: transform 0.3s ease;
}

.user-avatar i {
    font-size: 60px;
    color: rgba(255,255,255,0.9);
}

.team-card-modern:hover .user-avatar {
    transform: scale(1.1);
}

.team-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.team-card-modern:hover .team-card-overlay {
    opacity: 1;
}

.social-links {
    display: flex;
    gap: 15px;
}

.social-links a {
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.2);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.3s ease;
    border: 2px solid rgba(255,255,255,0.3);
}

.social-links a:hover {
    background: rgba(255,255,255,0.3);
}

.team-card-content {
    padding: 25px 20px;
    height: 130px; /* Fixed height for content area */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.team-name {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--title-color);
}

.team-designation {
    color: var(--theme-color);
    font-weight: 500;
    margin-bottom: 10px;
    display: block;
    font-size: 14px;
}

.team-bio {
    color: var(--body-color);
    line-height: 1.5;
    margin-bottom: 15px;
    font-size: 14px;
    flex-grow: 1;
}

.expertise-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.expertise-tags .tag {
    background: var(--theme-color);
    color: #fff;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 500;
}

/* Mission Statement Card */
.mission-statement-card {
    background: linear-gradient(135deg, var(--theme-color) 0%, var(--theme-color2) 100%);
    border-radius: 20px;
    padding: 50px;
    color: #fff;
    position: relative;
    overflow: hidden;
}

.mission-statement-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: url('path-to-pattern.svg') no-repeat;
    opacity: 0.1;
}

.mission-content {
    display: flex;
    align-items: center;
    gap: 40px;
    position: relative;
    z-index: 2;
}

.mission-icon {
    flex-shrink: 0;
    color: rgba(255,255,255,0.9);
}

.mission-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #fff;
}

.mission-description {
    font-size: 16px;
    line-height: 1.7;
    margin-bottom: 30px;
    color: rgba(255,255,255,0.9);
}

.mission-highlights {
    display: flex;
    gap: 40px;
}

.highlight-item {
    text-align: center;
}

.highlight-number {
    display: block;
    font-size: 32px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 5px;
}

.highlight-text {
    font-size: 14px;
    color: rgba(255,255,255,0.8);
}

/* Responsive Design */
@media (max-width: 768px) {
    .mission-content {
        flex-direction: column;
        text-align: center;
        gap: 30px;
    }
    
    .mission-highlights {
        justify-content: center;
        gap: 30px;
    }
    
    .mission-statement-card {
        padding: 30px 20px;
    }
    
    .team-card-modern {
        height: auto;
    }
    
    .team-card-content {
        height: auto;
    }
}
</style>

<!-- Why Choose Us Area -->
<section class="wcu-area-1 pb-120">
    <div class="container">
        <div class="row gy-40">
            <div class="col-xl-3 col-md-6">
                <div class="wcu-card">
                    <div class="box-icon">
                        <!-- SVG remains unchanged -->
                    </div>
                    <h4 class="box-title">Experienced Team</h4>
                    <p class="box-text">Our staff collectively possesses over 48 years of IT support experience across multiple African markets, with hands-on expertise in supporting fiscal devices since their launch in Zimbabwe.</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="wcu-card">
                    <div class="box-icon">
                        <!-- SVG remains unchanged -->
                    </div>
                    <h4 class="box-title">African Market Expertise</h4>
                    <p class="box-text">We have extensive experience working in Botswana, Zimbabwe, Namibia, Kenya and South Africa with blue-chip clients like Anglo American Corporation Zimbabwe and Debswana.</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="wcu-card">
                    <div class="box-icon">
                        <!-- SVG remains unchanged -->
                    </div>
                    <h4 class="box-title">Fiscal Device Specialists</h4>
                    <p class="box-text">We've been reliably supporting fiscal devices for the past eight years, currently supporting over 1,000 category C clients' fiscal devices.</p>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="wcu-card">
                    <div class="box-icon">
                        <!-- SVG remains unchanged -->
                    </div>
                    <h4 class="box-title">SME Focused Solutions</h4>
                    <p class="box-text">We help SMEs in Zimbabwe and the region align their technology investments with business processes through strategic consulting and financed hardware acquisition models.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Area -->
<section class="about-area-7 pt-120 pb-120">
    <div class="container">
        <div class="row gx-60 gy-60 align-items-center">
            <div class="col-xl-6">
                <div class="about-thumb6-1">
                    <div class="img1">
                        <img src="{{ asset('assets/img/others/FSSTeam.jpg') }}" alt="Fiscal Support Services team in Africa">
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="section__title mb-30">
                    <span class="sub-title text-anim">About Fiscal Support Services</span>
                    <h2 class="title text-anim2">Empowering African businesses through technology</h2>
                </div>
                <p class="mb-30">Fiscal Support Services (Private) Limited is an information technology company focused on delivering cost-effective and efficient technology solutions to businesses in emerging African markets. With a team that brings over 25 years of experience in the industry, we have been reliably supporting fiscal devices for the past eight years.</p>
                <p class="mb-35">We recognized the scarcity of right-sized IT services for African markets and incorporated to help businesses acquire hardware through financed models and deploy solutions that align IT investment cycles with business strategy. Our team has hands-on experience across multiple African countries, serving blue-chip clients while maintaining our commitment to SME growth and development.</p>

                <div class="tg-button-wrap mt-50">
                    <a href="{{ route('contact') }}" class="btn btn-six">
                        <i class="fas fa-play"></i>
                        <span class="btn-text" data-text="Contact Our Team"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection