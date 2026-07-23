<!DOCTYPE html>
<html>
<head>
    <title>Admission Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; padding: 20px; }
        .form-container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .section-title { background: #1a1a2e; color: white; padding: 10px 15px; margin-bottom: 20px; font-weight: 600; }
        .section-title i { margin-right: 8px; }
        .btn-submit { background: #667eea; color: white; padding: 12px 30px; border: none; cursor: pointer; }
        .btn-submit:hover { background: #5a67d8; }
        .required { color: red; }
    </style>
</head>
<body>
<div class="form-container">
    <h2 class="text-center mb-4">Admission Application Form</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.admissions.store') }}">
        @csrf

        <div class="section-title"><i class="fas fa-user-graduate"></i> Student Information</div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Student Name <span class="required">*</span></label>
                <input type="text" name="student_name" class="form-control" value="{{ old('student_name') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Place of Birth <span class="required">*</span></label>
                <input type="text" name="place_of_birth" class="form-control" value="{{ old('place_of_birth') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Date of Birth <span class="required">*</span></label>
                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Gender <span class="required">*</span></label>
                <select name="gender" class="form-control" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Religion</label>
                <input type="text" name="religion" class="form-control" value="{{ old('religion') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Nationality <span class="required">*</span></label>
                <input type="text" name="nationality" class="form-control" value="{{ old('nationality') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Former School</label>
                <input type="text" name="former_school" class="form-control" value="{{ old('former_school') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Shehia <span class="required">*</span></label>
                <input type="text" name="shehia" class="form-control" value="{{ old('shehia') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Ward <span class="required">*</span></label>
                <input type="text" name="ward" class="form-control" value="{{ old('ward') }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
            </div>
        </div>

        <div class="section-title"><i class="fas fa-user-friends"></i> Parent/Guardian Information</div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" name="parent_full_name" class="form-control" value="{{ old('parent_full_name') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Address <span class="required">*</span></label>
                <input type="text" name="parent_address" class="form-control" value="{{ old('parent_address') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Mobile <span class="required">*</span></label>
                <input type="text" name="parent_mobile" class="form-control" value="{{ old('parent_mobile') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Relationship <span class="required">*</span></label>
                <input type="text" name="parent_relationship" class="form-control" value="{{ old('parent_relationship') }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label>Email</label>
                <input type="email" name="parent_email" class="form-control" value="{{ old('parent_email') }}">
            </div>
        </div>

        <div class="section-title"><i class="fas fa-hand-holding-heart"></i> Sponsor Information</div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Full Name</label>
                <input type="text" name="sponsor_full_name" class="form-control" value="{{ old('sponsor_full_name') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Address</label>
                <input type="text" name="sponsor_address" class="form-control" value="{{ old('sponsor_address') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Mobile</label>
                <input type="text" name="sponsor_mobile" class="form-control" value="{{ old('sponsor_mobile') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label>Occupation</label>
                <input type="text" name="sponsor_occupation" class="form-control" value="{{ old('sponsor_occupation') }}">
            </div>
        </div>

        <div class="section-title"><i class="fas fa-book"></i> Academic Information</div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Academic Level <span class="required">*</span></label>
                <select name="academic_level" class="form-control" required>
                    <option value="">Select Level</option>
                    <option value="Primary">Primary</option>
                    <option value="Secondary">Secondary</option>
                    <option value="College">College</option>
                    <option value="University">University</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Class Applying For <span class="required">*</span></label>
                <select name="class_applying_for" class="form-control" required>
                    <option value="">Select Class</option>
                    <option value="Nursery">Nursery</option>
                    <option value="Primary 1">Primary 1</option>
                    <option value="Primary 2">Primary 2</option>
                    <option value="Primary 3">Primary 3</option>
                    <option value="Primary 4">Primary 4</option>
                    <option value="Primary 5">Primary 5</option>
                    <option value="Primary 6">Primary 6</option>
                    <option value="Form 1">Form 1</option>
                    <option value="Form 2">Form 2</option>
                    <option value="Form 3">Form 3</option>
                    <option value="Form 4">Form 4</option>
                    <option value="Form 5">Form 5</option>
                    <option value="Form 6">Form 6</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label>Academic Year <span class="required">*</span></label>
                <input type="text" name="academic_year" class="form-control" placeholder="2026/2027" value="{{ old('academic_year') }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label>Previous School</label>
                <input type="text" name="previous_school" class="form-control" value="{{ old('previous_school') }}">
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn-submit"><i class="fas fa-paper-plane"></i> Submit Application</button>
            <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
