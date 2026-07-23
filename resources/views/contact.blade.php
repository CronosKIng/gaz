@extends('layouts.app')

@section('title', 'Contact Us')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')
<!-- ===== CONTACT HERO ===== -->
<section class="contact-hero">
    <div class="container">
        <h1>Contact Us</h1>
        <p>We would love to hear from you. Get in touch with us through any of the methods below.</p>
    </div>
</section>

<!-- ===== CONTACT SECTION ===== -->
<section class="contact-section">
    <div class="container">
        <div class="row">
            <!-- Contact Info -->
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="contact-info-wrapper">
                    <h4>Get in Touch</h4>
                    
                    <div class="info-item">
                        <h6>Address</h6>
                        <p>Zanzibar, Tanzania</p>
                    </div>

                    <div class="info-item">
                        <h6>Phone</h6>
                        <p>+255 123 456 789</p>
                        <p class="sub-text">+255 987 654 321</p>
                    </div>

                    <div class="info-item">
                        <h6>Email</h6>
                        <p>info@gloriousacademy.com</p>
                        <p class="sub-text">admissions@gloriousacademy.com</p>
                    </div>

                    <div class="info-item">
                        <h6>Working Hours</h6>
                        <p>Monday - Friday: 7:30 AM - 5:00 PM</p>
                        <p class="sub-text">Saturday: 8:00 AM - 12:00 PM</p>
                    </div>

                    <div class="mt-4">
                        <h6 style="color: #B8860B; font-weight: 700; margin-bottom: 12px;">Follow Us</h6>
                        <div class="social-links">
                            <a href="#" class="facebook">Facebook</a>
                            <a href="#" class="twitter">Twitter</a>
                            <a href="#" class="instagram">Instagram</a>
                            <a href="#" class="whatsapp">WhatsApp</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="contact-form-wrapper">
                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Enter message subject" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" placeholder="Write your message here" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn-submit">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
