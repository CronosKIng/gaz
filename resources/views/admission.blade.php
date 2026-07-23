@extends('layouts.app')

@section('title', 'Admission')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admission.css') }}">
@endsection

@section('content')
<!-- ===== ADMISSION HERO ===== -->
<section class="admission-hero">
    <div class="container">
        <h1>Admission Application</h1>
        <p>Fill the form below to apply for admission at Glorious Academy</p>
    </div>
</section>

<!-- ===== ADMISSION FORM ===== -->
<section class="admission-form-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="admission-form">
                    <form action="{{ route('admission.store') }}" method="POST">
                        @csrf

                        <!-- Student Information -->
                        <div class="form-section">
                            <h5>Student Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="student_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('student_name') is-invalid @enderror" id="student_name" name="student_name" value="{{ old('student_name') }}" placeholder="Enter student full name" required>
                                    @error('student_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="place_of_birth" class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}" placeholder="Enter place of birth" required>
                                    @error('place_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <input type="text" class="form-control @error('religion') is-invalid @enderror" id="religion" name="religion" value="{{ old('religion') }}" placeholder="Enter religion">
                                    @error('religion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality') }}" placeholder="Enter nationality" required>
                                    @error('nationality')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="former_school" class="form-label">Former School</label>
                                    <input type="text" class="form-control @error('former_school') is-invalid @enderror" id="former_school" name="former_school" value="{{ old('former_school') }}" placeholder="Enter former school name">
                                    @error('former_school')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shehia" class="form-label">Shehia <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('shehia') is-invalid @enderror" id="shehia" name="shehia" value="{{ old('shehia') }}" placeholder="Enter shehia" required>
                                    @error('shehia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ward" class="form-label">Ward <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ward') is-invalid @enderror" id="ward" name="ward" value="{{ old('ward') }}" placeholder="Enter ward" required>
                                    @error('ward')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Parent/Guardian Information -->
                        <div class="form-section">
                            <h5>Parent / Guardian Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="parent_full_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('parent_full_name') is-invalid @enderror" id="parent_full_name" name="parent_full_name" value="{{ old('parent_full_name') }}" placeholder="Enter parent/guardian full name" required>
                                    @error('parent_full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="parent_address" class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('parent_address') is-invalid @enderror" id="parent_address" name="parent_address" value="{{ old('parent_address') }}" placeholder="Enter parent address" required>
                                    @error('parent_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="parent_mobile" class="form-label">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('parent_mobile') is-invalid @enderror" id="parent_mobile" name="parent_mobile" value="{{ old('parent_mobile') }}" placeholder="Enter mobile number" required>
                                    @error('parent_mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="parent_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('parent_relationship') is-invalid @enderror" id="parent_relationship" name="parent_relationship" value="{{ old('parent_relationship') }}" placeholder="E.g. Father, Mother, Guardian" required>
                                    @error('parent_relationship')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="parent_email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('parent_email') is-invalid @enderror" id="parent_email" name="parent_email" value="{{ old('parent_email') }}" placeholder="Enter email address">
                                    @error('parent_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sponsor Information -->
                        <div class="form-section">
                            <h5>Sponsor Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sponsor_full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('sponsor_full_name') is-invalid @enderror" id="sponsor_full_name" name="sponsor_full_name" value="{{ old('sponsor_full_name') }}" placeholder="Enter sponsor full name">
                                    @error('sponsor_full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sponsor_address" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('sponsor_address') is-invalid @enderror" id="sponsor_address" name="sponsor_address" value="{{ old('sponsor_address') }}" placeholder="Enter sponsor address">
                                    @error('sponsor_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sponsor_mobile" class="form-label">Mobile</label>
                                    <input type="text" class="form-control @error('sponsor_mobile') is-invalid @enderror" id="sponsor_mobile" name="sponsor_mobile" value="{{ old('sponsor_mobile') }}" placeholder="Enter sponsor mobile">
                                    @error('sponsor_mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sponsor_occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control @error('sponsor_occupation') is-invalid @enderror" id="sponsor_occupation" name="sponsor_occupation" value="{{ old('sponsor_occupation') }}" placeholder="Enter sponsor occupation">
                                    @error('sponsor_occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information -->
                        <div class="form-section">
                            <h5>Academic Information</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="academic_level" class="form-label">Academic Level <span class="text-danger">*</span></label>
                                    <select class="form-select @error('academic_level') is-invalid @enderror" id="academic_level" name="academic_level" required>
                                        <option value="">Select Level</option>
                                        <option value="Primary" {{ old('academic_level') == 'Primary' ? 'selected' : '' }}>Primary</option>
                                        <option value="Secondary" {{ old('academic_level') == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                                        <option value="College" {{ old('academic_level') == 'College' ? 'selected' : '' }}>College</option>
                                        <option value="University" {{ old('academic_level') == 'University' ? 'selected' : '' }}>University</option>
                                    </select>
                                    @error('academic_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="class_applying_for" class="form-label">Class Applying For <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_applying_for') is-invalid @enderror" id="class_applying_for" name="class_applying_for" required>
                                        <option value="">Select Class</option>
                                        <option value="Nursery" {{ old('class_applying_for') == 'Nursery' ? 'selected' : '' }}>Nursery</option>
                                        <option value="Primary 1" {{ old('class_applying_for') == 'Primary 1' ? 'selected' : '' }}>Primary 1</option>
                                        <option value="Primary 2" {{ old('class_applying_for') == 'Primary 2' ? 'selected' : '' }}>Primary 2</option>
                                        <option value="Primary 3" {{ old('class_applying_for') == 'Primary 3' ? 'selected' : '' }}>Primary 3</option>
                                        <option value="Primary 4" {{ old('class_applying_for') == 'Primary 4' ? 'selected' : '' }}>Primary 4</option>
                                        <option value="Primary 5" {{ old('class_applying_for') == 'Primary 5' ? 'selected' : '' }}>Primary 5</option>
                                        <option value="Primary 6" {{ old('class_applying_for') == 'Primary 6' ? 'selected' : '' }}>Primary 6</option>
                                        <option value="Form 1" {{ old('class_applying_for') == 'Form 1' ? 'selected' : '' }}>Form 1</option>
                                        <option value="Form 2" {{ old('class_applying_for') == 'Form 2' ? 'selected' : '' }}>Form 2</option>
                                        <option value="Form 3" {{ old('class_applying_for') == 'Form 3' ? 'selected' : '' }}>Form 3</option>
                                        <option value="Form 4" {{ old('class_applying_for') == 'Form 4' ? 'selected' : '' }}>Form 4</option>
                                        <option value="Form 5" {{ old('class_applying_for') == 'Form 5' ? 'selected' : '' }}>Form 5</option>
                                        <option value="Form 6" {{ old('class_applying_for') == 'Form 6' ? 'selected' : '' }}>Form 6</option>
                                    </select>
                                    @error('class_applying_for')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('academic_year') is-invalid @enderror" id="academic_year" name="academic_year" value="{{ old('academic_year') }}" placeholder="2026/2027" required>
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="previous_school" class="form-label">Previous School</label>
                                    <input type="text" class="form-control @error('previous_school') is-invalid @enderror" id="previous_school" name="previous_school" value="{{ old('previous_school') }}" placeholder="Enter previous school name">
                                    @error('previous_school')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-section">
                            <h5>Address Information</h5>
                            <div class="mb-3">
                                <label for="address" class="form-label">Home Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="4" placeholder="Enter your home address" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn-submit">Submit Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection