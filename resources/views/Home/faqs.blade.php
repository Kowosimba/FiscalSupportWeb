@extends('components.homelayout')
@section('home-content')

<x-breadcrumb>FAQs</x-breadcrumb>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --fss-primary: #216115;
        --fss-primary-light: #2e8b57;
        --fss-primary-dark: #1a4d10;
        --fss-secondary: #4caf50;
        --fss-accent: #8bc34a;
        --fss-dark: #263238;
        --fss-gray: #607d8b;
        --fss-light-gray: #eceff1;
        --fss-white: #ffffff;
        --fss-bg-color: #f5f9f5;
        --fss-card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .fss-faq-container * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'DM Sans', 'Poppins', sans-serif;
    }

    .fss-faq-container {
        background-color: var(--fss-bg-color);
        color: var(--fss-dark);
        line-height: 1.6;
        padding: 2rem 0;
    }

    .fss-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* FAQ Main Content */
    .fss-faq-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
    }

    /* Sidebar */
    .fss-faq-sidebar {
        position: sticky;
        top: 120px;
        height: fit-content;
    }

    .fss-faq-categories {
        background: var(--fss-white);
        border-radius: 10px;
        box-shadow: var(--fss-card-shadow);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .fss-faq-categories h3 {
        color: var(--fss-primary);
        margin-bottom: 1.25rem;
        font-size: 1.2rem;
        font-weight: 600;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .fss-faq-categories h3::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 3px;
        background: var(--fss-accent);
        border-radius: 3px;
    }

    .fss-faq-category-list {
        list-style: none;
    }

    .fss-faq-category-list li {
        margin-bottom: 0.75rem;
    }

    .fss-faq-category-list li:last-child {
        margin-bottom: 0;
    }

    .fss-faq-category-list a {
        display: block;
        padding: 0.75rem 1rem;
        color: var(--fss-gray);
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-weight: 500;
        border-left: 3px solid transparent;
    }

    .fss-faq-category-list a:hover,
    .fss-faq-category-list a.active {
        background: rgba(33, 97, 21, 0.05);
        color: var(--fss-primary);
        border-left: 3px solid var(--fss-primary);
    }

    .fss-sidebar-cta {
        background: linear-gradient(135deg, var(--fss-primary), var(--fss-primary-dark));
        color: var(--fss-white);
        border-radius: 10px;
        padding: 1.75rem;
        text-align: center;
        box-shadow: var(--fss-card-shadow);
    }

    .fss-sidebar-cta h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .fss-sidebar-cta p {
        margin-bottom: 1.25rem;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .fss-cta-contacts {
        margin-bottom: 1.5rem;
    }

    .fss-cta-contacts a {
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--fss-white);
        margin-bottom: 0.75rem;
        text-decoration: none;
        transition: opacity 0.3s;
        font-size: 0.95rem;
    }

    .fss-cta-contacts a:hover {
        opacity: 0.9;
    }

    .fss-cta-contacts i {
        margin-right: 8px;
        font-size: 1rem;
    }

    .fss-cta-btn {
        display: inline-block;
        background: var(--fss-white);
        color: var(--fss-primary);
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .fss-cta-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        background: #f0f0f0;
    }

    /* FAQ Content */
    .fss-faq-section {
        margin-bottom: 2.5rem;
    }

    .fss-faq-section:last-child {
        margin-bottom: 0;
    }

    .fss-faq-section h2 {
        color: var(--fss-primary);
        margin-bottom: 1.5rem;
        font-size: 1.75rem;
        position: relative;
        padding-bottom: 0.75rem;
        font-weight: 700;
    }

    .fss-faq-section h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: var(--fss-accent);
        border-radius: 2px;
    }

    .fss-faq-accordion {
        background: var(--fss-white);
        border-radius: 10px;
        box-shadow: var(--fss-card-shadow);
        overflow: hidden;
    }

    .fss-accordion-item {
        border-bottom: 1px solid var(--fss-light-gray);
        transition: all 0.3s ease;
    }

    .fss-accordion-item:last-child {
        border-bottom: none;
    }

    .fss-accordion-header {
        padding: 0;
    }

    .fss-accordion-button {
        width: 100%;
        background: none;
        border: none;
        text-align: left;
        padding: 1.25rem 1.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--fss-dark);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .fss-accordion-button:hover {
        background: rgba(33, 97, 21, 0.05);
    }

    .fss-accordion-button:focus {
        outline: none;
    }

    .fss-accordion-button::after {
        content: '\f078';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        transition: transform 0.3s ease;
        color: var(--fss-primary);
    }

    .fss-accordion-button.collapsed::after {
        transform: rotate(0deg);
    }

    .fss-accordion-button:not(.collapsed)::after {
        transform: rotate(180deg);
    }

    .fss-accordion-body {
        padding: 0 1.5rem;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out, padding 0.3s ease;
    }

    .fss-accordion-body.show {
        padding: 0 1.5rem 1.5rem;
        max-height: 1000px;
    }

    .fss-accordion-body p {
        margin-bottom: 1rem;
        color: var(--fss-gray);
        line-height: 1.7;
    }

    .fss-accordion-body ul, .fss-accordion-body ol {
        margin-bottom: 1rem;
        padding-left: 1.25rem;
    }

    .fss-accordion-body li {
        margin-bottom: 0.5rem;
        color: var(--fss-gray);
        line-height: 1.6;
    }

    .fss-accordion-body strong {
        color: var(--fss-primary-dark);
    }

    /* Responsive Styles */
    @media (max-width: 992px) {
        .fss-faq-grid {
            grid-template-columns: 1fr;
        }
        
        .fss-faq-sidebar {
            position: static;
            margin-bottom: 2rem;
        }
    }

    @media (max-width: 768px) {
        .fss-accordion-button {
            padding: 1rem 1.25rem;
            font-size: 1rem;
        }
        
        .fss-faq-categories {
            padding: 1.25rem;
        }
        
        .fss-sidebar-cta {
            padding: 1.5rem;
        }
    }
</style>

<!-- FAQ Main Content -->
<main class="fss-faq-container">
    <div class="fss-container">
        <div class="fss-faq-grid">
            <!-- Sidebar -->
            <aside class="fss-faq-sidebar">
                <div class="fss-faq-categories">
                    <h3>FAQ Categories</h3>
                    <ul class="fss-faq-category-list">
                        @foreach($categories as $category)
                            <li>
                                <a href="#fss-{{ $category->slug }}" class="{{ $loop->first ? 'active' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <div class="fss-sidebar-cta">
                    <h3>Need Immediate Support?</h3>
                    <p>Our fiscal experts are ready to assist with any technical issues.</p>
                    <div class="fss-cta-contacts">
                        <a href="tel:+2638677105462">+2638677105462</a>
                        <a href="mailto:supporthre2@fiscalsupportservices.com">Email Us</a>
                    </div>
                    <a href="{{ route('contact') }}" class="fss-cta-btn">Get A Quote</a>
                </div>
            </aside>

            <!-- FAQ Content -->
            <div class="fss-faq-content">
                @foreach($categories as $category)
                    @if($category->activeFaqs->count() > 0)
                        <section id="fss-{{ $category->slug }}" class="fss-faq-section">
                            <h2>{{ $category->name }}</h2>
                            <div class="fss-faq-accordion" id="fss-{{ $category->slug }}Accordion">
                                @foreach($category->activeFaqs as $faq)
                                    <div class="fss-accordion-item">
                                        <h3 class="fss-accordion-header">
                                            <button class="fss-accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fss-faq-{{ $faq->id }}">
                                                {{ $faq->question }}
                                            </button>
                                        </h3>
                                        <div id="fss-faq-{{ $faq->id }}" class="fss-accordion-body collapse">
                                            {!! $faq->answer !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</main>

<script>
    // Simple accordion functionality
    document.addEventListener('DOMContentLoaded', function() {
        const accordionButtons = document.querySelectorAll('.fss-accordion-button');
        
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-bs-target');
                const target = document.querySelector(targetId);
                
                // Toggle the show class
                target.classList.toggle('show');
                
                // Toggle the collapsed class on the button
                this.classList.toggle('collapsed');
                
                // Close other open accordion items in the same section
                const parentAccordion = this.closest('.fss-faq-accordion');
                if (parentAccordion) {
                    const allBodies = parentAccordion.querySelectorAll('.fss-accordion-body');
                    allBodies.forEach(body => {
                        if (body !== target && body.classList.contains('show')) {
                            body.classList.remove('show');
                            const correspondingButton = body.previousElementSibling.querySelector('.fss-accordion-button');
                            if (correspondingButton) {
                                correspondingButton.classList.add('collapsed');
                            }
                        }
                    });
                }
            });
        });
        
        // Smooth scrolling for category links
        const categoryLinks = document.querySelectorAll('.fss-faq-category-list a');
        categoryLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                // Update active category
                categoryLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                // Scroll to section
                if (targetSection) {
                    window.scrollTo({
                        top: targetSection.offsetTop - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>

@endsection