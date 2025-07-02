@extends('components.homelayout')

@section('home-content')
<style>
:root {
    --primary: #08652a;
    --primary-light: #0d712d;
    --primary-dark: #099d4c;
    --secondary: #0b582b;
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

.service-details-area {
    padding: 80px 0;
    background-color: #f8f9fa;
}

.service-hero {
    position: relative;
    margin-bottom: 40px;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

.service-hero-image {
    height: 400px;
    width: 100%;
    object-fit: cover;
}

.service-hero-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 30px;
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    color: white;
}

.service-hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.service-content-card {
    background: white;
    border-radius: var(--radius-lg);
    padding: 40px;
    box-shadow: var(--shadow-md);
    margin-bottom: 40px;
}

.service-content-title {
    color: var(--primary);
    margin-bottom: 25px;
    font-size: 1.8rem;
    font-weight: 700;
    position: relative;
    padding-bottom: 15px;
}

.service-content-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: var(--primary);
    border-radius: 2px;
}

.service-content-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-light);
}

/* Process Steps */
.process-section {
    margin-top: 60px;
}

.process-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.process-card {
    background: white;
    border-radius: var(--radius-md);
    padding: 30px;
    box-shadow: var(--shadow-sm);
    border: 1px solid rgba(0,0,0,0.05);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.process-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.process-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: var(--primary);
}

.process-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 15px;
}

.process-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 15px;
}

.process-text {
    color: var(--text-light);
    line-height: 1.6;
}

/* Sidebar */
.sidebar-widget {
    background: white;
    border-radius: var(--radius-md);
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: var(--shadow-sm);
}

.widget-title {
    color: var(--primary);
    margin-bottom: 20px;
    font-size: 1.4rem;
    font-weight: 600;
}

.service-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.service-item {
    border-bottom: 1px solid #eee;
}

.service-item:last-child {
    border-bottom: none;
}

.service-link {
    display: block;
    padding: 12px 0;
    color: var(--text-light);
    text-decoration: none;
    transition: var(--transition);
}

.service-link:hover {
    color: var(--primary);
    padding-left: 10px;
}

/* Download Cards */
.download-card {
    display: flex;
    align-items: center;
    padding: 15px;
    background: white;
    border-radius: var(--radius-md);
    margin-bottom: 15px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.download-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.download-icon {
    width: 50px;
    height: 50px;
    background: var(--primary);
    color: white;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 1.25rem;
}

.download-info {
    flex-grow: 1;
}

.download-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text);
    margin-bottom: 5px;
}

.download-meta {
    font-size: 0.875rem;
    color: var(--text-light);
}

.download-btn {
    width: 40px;
    height: 40px;
    background: var(--primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: var(--transition);
}

.download-btn:hover {
    background: var(--primary-dark);
    color: white;
}

/* Contact Widget */
.contact-widget {
    background: var(--primary);
    color: white;
    border-radius: var(--radius-md);
    padding: 30px;
    text-align: center;
}

.contact-title {
    color: white;
    margin-bottom: 15px;
}

.contact-text {
    margin-bottom: 20px;
    opacity: 0.9;
}

.contact-phone {
    display: block;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 25px;
    font-weight: 700;
    text-decoration: none;
}

.contact-phone:hover {
    color: white;
    text-decoration: underline;
}

.contact-btn {
    display: inline-block;
    background: white;
    color: var(--primary);
    padding: 12px 25px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.contact-btn:hover {
    background: rgba(255,255,255,0.9);
    color: var(--primary);
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 991px) {
    .service-hero-image {
        height: 300px;
    }
    
    .service-content-card,
    .sidebar-widget {
        padding: 25px;
    }
}

@media (max-width: 767px) {
    .service-hero-image {
        height: 250px;
    }
    
    .service-hero-title {
        font-size: 2rem;
    }
    
    .process-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Breadcrumb -->
<x-breadcrumb>Service Details</x-breadcrumb>

<!-- Service Details -->
<section class="service-details-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Service Hero -->
                <div class="service-hero">
                    @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" class="service-hero-image">
                    @else
                        <div class="service-hero-image bg-dark d-flex align-items-center justify-content-center">
                            <h3 class="text-white mb-0">{{ $service->title }}</h3>
                        </div>
                    @endif
                    <div class="service-hero-overlay">
                        <h1 class="service-hero-title">{{ $service->title }}</h1>
                    </div>
                </div>
                
                <!-- Service Content -->
                <div class="service-content-card">
                    <h2 class="service-content-title">About This Service</h2>
                    <div class="service-content-text">
                        {!! $service->content !!}
                    </div>
                </div>
                
                <!-- Process Steps -->
                @if($service->process_steps)
                <div class="service-content-card process-section">
                    <h2 class="service-content-title">Our Process</h2>
                    <div class="process-grid">
                        @foreach(json_decode($service->process_steps, true) as $step)
                        <div class="process-card">
                            <div class="process-number">0{{ $loop->iteration }}</div>
                            <h3 class="process-title">{{ $step['title'] }}</h3>
                            <p class="process-text">{{ $step['description'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Related Services -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Our Services</h3>
                    <ul class="service-list">
                        @foreach($relatedServices as $related)
                        <li class="service-item">
                            <a href="{{ route('services.show', $related->slug) }}" class="service-link">
                                {{ $related->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Resources -->
                @if($service->resources->count() > 0)
                <div class="sidebar-widget">
                    <h3 class="widget-title">Download Resources</h3>
                    @foreach($service->resources as $resource)
                    <div class="download-card">
                        <div class="download-icon">
                            @if($resource->file_type === 'pdf')
                                <i class="fas fa-file-pdf"></i>
                            @elseif(in_array($resource->file_type, ['doc', 'docx']))
                                <i class="fas fa-file-word"></i>
                            @elseif(in_array($resource->file_type, ['xls', 'xlsx']))
                                <i class="fas fa-file-excel"></i>
                            @elseif(in_array($resource->file_type, ['ppt', 'pptx']))
                                <i class="fas fa-file-powerpoint"></i>
                            @else
                                <i class="fas fa-file-alt"></i>
                            @endif
                        </div>
                        <div class="download-info">
                            <h4 class="download-title">{{ $resource->title }}</h4>
                            <div class="download-meta">{{ strtoupper($resource->file_type) }} • {{ $resource->file_size }}</div>
                        </div>
                        <a href="{{ route('service-resource.download', $resource->id) }}" class="download-btn" download>

                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <!-- Contact Widget -->
                <div class="contact-widget">
                    <h3 class="widget-title contact-title">Need Help?</h3>
                    <p class="contact-text">Our experts are ready to assist you with any questions about this service.</p>
                    <a href="tel:+1234567890" class="contact-phone">
                        <i class="fas fa-phone-alt me-2"></i> +1 (234) 567-890
                    </a>
                    <a href="{{ route('contact') }}" class="contact-btn">
                        <i class="fas fa-envelope me-2"></i> Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection