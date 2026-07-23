@extends('layouts.app')

@section('title', 'Home')

@section('styles')
<style>
    .program-section {
        padding: 80px 0;
    }
    
    .program-section:nth-child(even) {
        background-color: #f8f9fa;
    }
    
    .program-section .btn-apply {
        background: linear-gradient(135deg, #D9A52A, #B8860B);
        color: white;
        border: none;
        padding: 12px 35px;
        border-radius: 50px;
        font-weight: 600;
        transition: transform 0.3s ease;
    }
    
    .program-section .btn-apply:hover {
        transform: scale(1.05);
        color: white;
        box-shadow: 0 5px 20px rgba(217, 165, 42, 0.5);
    }
    
    .program-description {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.8;
    }
    
    .program-image {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .program-image img {
        width: 100%;
        height: 350px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .program-image img:hover {
        transform: scale(1.05);
    }
</style>
@endsection

@section('content')
<!-- ===== HERO SECTION ===== -->
<section class="hero-section">
    <!-- Full screen image (not background) -->
    <div class="hero-image-full">
        <img src="https://i.ibb.co/wFKbWY6w/hero.png" alt="Glorious Academy">
    </div>
    
    <!-- Gold overlay -->
    <div class="hero-overlay-gold"></div>
    
    <!-- Content -->
    <div class="hero-content">
        <h1>Welcome to Glorious Academy</h1>
        <p>Nurturing Excellence from Nursery to Advanced Level in Zanzibar</p>
        <a href="{{ route('admission') }}" class="btn">Enroll Now</a>
    </div>
</section>

<!-- ===== INTRODUCTION ===== -->
<section class="program-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="section-title">
                    <h2>About Glorious Academy</h2>
                    <p>Providing Quality Education in Zanzibar</p>
                </div>
                <p class="program-description">
                    <strong>Glorious Academy</strong> is a premier educational institution in Zanzibar, 
                    committed to providing holistic and quality education from <strong>Nursery</strong> 
                    through to <strong>Advanced Level</strong>. Our mission is to nurture young minds 
                    and prepare them for a bright future through innovative teaching methods and a 
                    supportive learning environment.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ===== NURSERY PROGRAM ===== -->
<section class="program-section" style="background-color: #f0f7ff;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <div class="program-image">
                    <img src="https://i.ibb.co/b5Lk6Qh8/nusery.jpg" alt="Nursery Program">
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <h3 class="program-title">Nursery Program</h3>
                <p class="program-description">
                    Our <strong>Nursery Program</strong> provides a nurturing and stimulating environment 
                    for children aged 3-5 years. We focus on early childhood development through play-based 
                    learning, social skills, and foundational education that prepares children for primary 
                    education.
                </p>
                <ul class="program-list">
                    <li>Ages: 3-5 years</li>
                    <li>Play-based learning approach</li>
                    <li>Social and emotional development</li>
                    <li>Foundation for primary education</li>
                </ul>
                <a href="{{ route('admission') }}" class="btn-apply">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<!-- ===== PRIMARY PROGRAM ===== -->
<section class="program-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="program-image">
                    <img src="https://i.ibb.co/wFKbWY6w/hero.png" alt="Primary Program">
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="program-title">Primary Program</h3>
                <p class="program-description">
                    Our <strong>Primary Program</strong> covers Standard 1 to Standard 6, providing a 
                    comprehensive curriculum that builds strong foundations in core subjects including 
                    Mathematics, English, Science, and Social Studies. We focus on developing critical 
                    thinking and problem-solving skills.
                </p>
                <ul class="program-list">
                    <li>Standards 1 - 6</li>
                    <li>Comprehensive curriculum</li>
                    <li>Critical thinking development</li>
                    <li>Preparation for secondary education</li>
                </ul>
                <a href="{{ route('admission') }}" class="btn-apply">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<!-- ===== SECONDARY PROGRAM ===== -->
<section class="program-section" style="background-color: #f0f7ff;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <div class="program-image">
                    <img src="https://i.ibb.co/nNYMSFKL/secondary.jpg" alt="Secondary Program">
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <h3 class="program-title">Secondary Program</h3>
                <p class="program-description">
                    Our <strong>Secondary Program</strong> covers Form 1 to Form 4, offering a broad 
                    and balanced curriculum that prepares students for national examinations and further 
                    education. We emphasize both academic excellence and character development.
                </p>
                <ul class="program-list">
                    <li>Forms 1 - 4</li>
                    <li>National examination preparation</li>
                    <li>Character and leadership development</li>
                    <li>Pathway to Advanced Level</li>
                </ul>
                <a href="{{ route('admission') }}" class="btn-apply">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<!-- ===== ADVANCED LEVEL PROGRAM ===== -->
<section class="program-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="program-image">
                    <img src="https://i.ibb.co/x83Mtb4J/advance.jpg" alt="Advanced Level Program">
                </div>
            </div>
            <div class="col-lg-6">
                <h3 class="program-title">Advanced Level Program</h3>
                <p class="program-description">
                    Our <strong>Advanced Level Program</strong> covers Form 5 to Form 6, offering 
                    specialized subjects that prepare students for university education and professional 
                    careers. We provide focused learning in science, arts, and commercial streams.
                </p>
                <ul class="program-list">
                    <li>Forms 5 - 6</li>
                    <li>Specialized subject streams</li>
                    <li>University preparation</li>
                    <li>Career guidance and counseling</li>
                </ul>
                <a href="{{ route('admission') }}" class="btn-apply">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<!-- ===== LOCATION / IFRAME ===== -->
<section class="location-section">
    <div class="container">
        <div class="section-title">
            <h2>Find Us</h2>
            <p>Visit us at our campus in Zanzibar, Tanzania</p>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="location-frame">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.60408081183!2d39.213851874990006!3d-6.183709793803824!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185cda7536a133bf%3A0xdd75d10148730d1f!2sGlorious%20Academy!5e0!3m2!1sen!2stz!4v1784232700458!5m2!1sen!2stz" 
                        width="100%" 
                        height="450" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="strict-origin-when-cross-origin">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
