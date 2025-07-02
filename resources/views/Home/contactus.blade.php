@extends('components.homelayout')
@section('home-content')

    <x-breadcrumb>Contact Us</x-breadcrumb>

    <section class="ct-contact-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="ct-contact-card p-4 p-lg-5 h-100">
                        <h2 class="ct-section-title mb-4">Connect With Us</h2>
                        <p class="ct-lead-text mb-4">We're here to help and answer any questions you might have.</p>

                        <div class="ct-contact-info">
                            <div class="ct-info-item d-flex mb-4">
                                <div class="ct-icon-wrapper me-4">
                                    <i class="fas fa-map-marker-alt ct-icon"></i>
                                </div>
                                <div>
                                    <h3 class="ct-info-heading">Our Locations</h3>
                                    <p class="ct-info-text">Main Office: 36 East Road, Belgravia, Harare</p>
                                    <p class="ct-info-text">Branch Office: 209 Yorkhouse Building 8th Ave, Cnr Hebert Chitepo ,Bulawayo</p>
                                </div>
                            </div>

                            <div class="ct-info-item d-flex mb-4">
                                <div class="ct-icon-wrapper me-4">
                                    <i class="fas fa-phone-alt ct-icon"></i>
                                </div>
                                <div>
                                    <h3 class="ct-info-heading">Call Us</h3>
                                    <p class="ct-info-text">Main Office: <a href="tel:+263292270666" class="ct-link">+263292270666/70668</a></p>
                                    <p class="ct-info-text">Support Line: <a href="tel:+263775622934" class="ct-link">+263 775 622 934</a></p>
                                </div>
                            </div>

                            <div class="ct-info-item d-flex mb-4">
                                <div class="ct-icon-wrapper me-4">
                                    <i class="fas fa-envelope ct-icon"></i>
                                </div>
                                <div>
                                    <h3 class="ct-info-heading">Email Us</h3>
                                    <p class="ct-info-text">
                                        <a href="mailto:sales@fiscalsupportservices.com" class="ct-link">sales@fiscalsupportservices</a>
                                    </p>
                                    <p class="ct-info-text">
                                        <a href="mailto:supporthre2@fiscalsupportservices.com" class="ct-link">supporthre@fiscalsupportservices.com</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="ct-map-card h-100">
                        <div class="ct-map-container mb-4">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.8354345093747!2d31.0420659!3d-17.8029538!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1931a572d71e70f1%3A0xe978e14339339f68!2sFiscal+Support+Services!5e0!3m2!1sen!2sus!4v1622549400000!5m2!1sen!2sus"
                                width="100%"
                                height="300"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                class="ct-map-iframe"
                            ></iframe>
                        </div>

                        <div class="ct-contact-form p-4">
                            <h3 class="ct-form-title mb-3">Send Us a Message</h3>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif
                            <form id="contactForm" method="POST" action="{{ route('send_email') }}">
                                @csrf
                                <div class="mb-3">
                                    <input type="text" name="name" class="form-control ct-form-input" placeholder="Your Name" required>
                                </div>
                                <div class="mb-3">
                                    <input type="email" name="email" class="form-control ct-form-input" placeholder="Your Email" required>
                                </div>
                                <div class="mb-3">
                                    <textarea name="message" class="form-control ct-form-input" rows="3" placeholder="Your Message" required></textarea>
                                </div>
                                <button type="submit" class="btn ct-submit-btn w-100">Send Message</button>
                            </form>
                            @if (session('message'))
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        Swal.fire({
                                            title: "Message",
                                            text: "{{ session('message') }}",
                                            icon: "success",
                                            confirmButtonText: "OK",
                                        });
                                    });
                                </script>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection