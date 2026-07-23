<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glorious Academy - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #FFCC00;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 450px;
            width: 100%;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 100px;
            height: auto;
        }
        .logo h2 {
            font-family: Impact, sans-serif;
            color: #333333;
            font-size: 28px;
            margin-top: 10px;
        }
        .form-control {
            height: 45px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btn-login {
            background: #1a1a2e;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #333;
            color: white;
        }
        .alert {
            border-radius: 5px;
        }
        .forgot-password {
            color: #666;
            font-size: 14px;
            text-decoration: none;
        }
        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="login-container">
    <div class="logo">
        <img src="{{ asset('images/gaslogo.png') }}" alt="Glorious Academy Logo">
        <h2>GLORIOUS ACADEMY</h2>
    </div>

    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('glorious.login') }}">
        @csrf
        <div class="mb-3">
            <input type="text" name="exam" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="pass" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="btn-login">Login</button>
    </form>

    <div class="text-center mt-3">
        <a href="#" class="forgot-password">Forgotten Password..?</a>
    </div>

    <div class="text-center mt-3">
        <small class="text-muted">&copy; {{ date('Y') }} Glorious Academy</small>
    </div>
</div>
</body>
</html>
