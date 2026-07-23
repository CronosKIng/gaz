@extends('layouts.app')

@section('title', 'About Us')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endsection

@section('content')
<!-- ===== ABOUT HERO ===== -->
<section class="about-hero">
    <div class="container">
        <h1>About Glorious Academy</h1>
        <p>Discover the excellence in education that we offer from Nursery to Advanced Level</p>
    </div>
</section>

<!-- ===== ABOUT CONTENT ===== -->
<section class="about-content">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image">
                    <img src="https://i.ibb.co/qL18zmGb/jengo.png" alt="Glorious Academy Building">
                </div>
            </div>
            <div class="col-lg-6">
                <h2>Welcome to Glorious Academy</h2>
                <p>
                    <strong>Glorious Academy</strong> is one of the leading educational institutions in 
                    Zanzibar, Tanzania. We are committed to providing quality education and nurturing 
                    young minds from <strong>Nursery</strong> through to <strong>Advanced Level</strong>.
                </p>
                <p>
                    Located in the heart of Zanzibar, our school offers a conducive learning environment 
                    with modern facilities and well-equipped classrooms. We believe in holistic education 
                    that combines academic excellence with character development and moral values.
                </p>
                <p>
                    Our dedicated team of qualified and experienced teachers ensures that every child 
                    receives personalized attention and guidance to reach their full potential. We are 
                    proud to be one of the best schools in Zanzibar and Tanzania.
                </p>
                <p>
                    At Glorious Academy, we provide:
                </p>
                <ul class="program-list" style="margin-top: 10px;">
                    <li>Quality education from Nursery to Advanced Level</li>
                    <li>Modern classrooms with advanced learning resources</li>
                    <li>Experienced and qualified teaching staff</li>
                    <li>Safe and conducive learning environment</li>
                    <li>Holistic development including sports and extracurricular activities</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ===== FEATURES ===== -->
<section class="about-features">
    <div class="container">
        <div class="section-title">
            <h2>Why Choose Glorious Academy</h2>
            <p>We provide the best learning environment for your child's future</p>
        </div>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="feature-box">
                    <h4>Quality Education</h4>
                    <p>Comprehensive curriculum from Nursery to Advanced Level with modern teaching methods</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-box">
                    <h4>Modern Facilities</h4>
                    <p>Well-equipped classrooms, library, laboratories, and sports facilities</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-box">
                    <h4>Qualified Teachers</h4>
                    <p>Experienced and dedicated teachers who nurture every child's potential</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="feature-box">
                    <h4>Holistic Development</h4>
                    <p>Sports, arts, and extracurricular activities for well-rounded growth</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<section class="about-stats">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6 mb-3 mb-md-0">
                <div class="stat-box">
                    <h3>500+</h3>
                    <p>Students Enrolled</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0">
                <div class="stat-box">
                    <h3>30+</h3>
                    <p>Qualified Teachers</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0">
                <div class="stat-box">
                    <h3>15+</h3>
                    <p>Years of Excellence</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3 mb-md-0">
                <div class="stat-box">
                    <h3>100%</h3>
                    <p>Student Satisfaction</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
