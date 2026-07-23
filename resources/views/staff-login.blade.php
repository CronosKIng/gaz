<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff Login - Glorious Academy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/staff-login.css') }}">
</head>
<body>
    <div class="login-page">
        <!-- Left Side - Logo/Branding -->
        <div class="login-left">
            <div class="logo-container">
                <img src="https://i.ibb.co/gMfmMQ2B/logo.png" alt="Glorious Academy">
                <h1>Glorious Academy</h1>
                <p>Quality Education from Nursery to Advanced Level in Zanzibar</p>
            </div>
            <div class="brand-features">
                <div class="feature-item">
                    <span class="dot"></span>
                    <span>Nursery to Advanced Level</span>
                </div>
                <div class="feature-item">
                    <span class="dot"></span>
                    <span>Qualified and Experienced Teachers</span>
                </div>
                <div class="feature-item">
                    <span class="dot"></span>
                    <span>Modern Learning Facilities</span>
                </div>
                <div class="feature-item">
                    <span class="dot"></span>
                    <span>Holistic Education and Character Development</span>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-card">
                <div class="login-header">
                    <h2>Staff Login</h2>
                    <p>Enter your credentials to access the dashboard</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('staff.login.submit') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="user">Username</label>
                        <div class="input-wrapper">
                            <input type="text" id="user" name="user" value="{{ old('user') }}" placeholder="Enter your username" required autofocus class="@error('user') is-invalid @enderror">
                            @error('user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Enter your password" required class="@error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            Remember Me
                        </label>
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>

                <div class="login-footer">
                    <p>&copy; {{ date('Y') }} Glorious Academy. All rights reserved.</p>
                    <p style="margin-top: 5px;">
                        <a href="{{ route('home') }}">Back to Home</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
