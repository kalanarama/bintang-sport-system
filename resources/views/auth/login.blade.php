<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Login - Bintang Sport Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        .wrapper {
            display: flex;
            height: 100vh;
        }

        .left-side {
            flex: 1;
            background: linear-gradient(135deg, rgba(15,74,156,0.85), rgba(21,101,192,0.85), rgba(30,92,255,0.85));
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            animation: slideInLeft 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        .left-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('{{ asset('img/rumput-lapangan.jpg') }}') no-repeat center/cover;
            opacity: 0.3;
            z-index: 1;
        }

        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            animation: floatCircle 8s ease-in-out infinite;
            z-index: 2;
        }
        .circle-1 { width: 350px; height: 350px; top: -80px; left: -80px; animation-delay: 0s; }
        .circle-2 { width: 250px; height: 250px; bottom: -60px; right: -60px; animation-delay: 2s; }
        .circle-3 { width: 180px; height: 180px; top: 40%; left: 60%; animation-delay: 4s; }

        @keyframes floatCircle {
            0%, 100% { transform: scale(1) translate(0, 0); }
            50% { transform: scale(1.1) translate(10px, -10px); }
        }

        .float-icon {
            position: absolute;
            color: rgba(255,255,255,0.12);
            animation: floatIcon 6s ease-in-out infinite;
            z-index: 2;
        }
        .float-icon:nth-child(4) { font-size: 50px; top: 12%; left: 8%; animation-delay: 0s; }
        .float-icon:nth-child(5) { font-size: 35px; top: 65%; left: 6%; animation-delay: 1.5s; }
        .float-icon:nth-child(6) { font-size: 42px; top: 25%; right: 10%; animation-delay: 3s; }
        .float-icon:nth-child(7) { font-size: 38px; top: 75%; right: 8%; animation-delay: 4.5s; }

        @keyframes floatIcon {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: 0.12; }
            50% { transform: translateY(-18px) rotate(12deg); opacity: 0.22; }
        }

        .left-content {
            position: relative;
            z-index: 3;
            text-align: center;
            max-width: 420px;
            padding: 0 30px;
        }

        .logo {
            margin-bottom: 25px;
            animation: popIn 0.9s cubic-bezier(0.34, 1.56, 0.64, 1) 0.5s both;
        }

        @keyframes popIn {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .logo img {
            width: 220px;
            filter: drop-shadow(0 0 15px rgba(100,180,255,0.9)) drop-shadow(0 0 30px rgba(100,180,255,0.6));
        }

        .tagline {
            font-size: 1.15rem;
            line-height: 1.6;
            font-weight: 500;
            animation: fadeInUp 0.8s ease 0.9s both;
        }

        .tagline span {
            color: #ffffff;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(100,180,255,0.9), 0 0 25px rgba(100,180,255,0.7);
        }

        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .right-side {
            flex: 1;
            background: white;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: slideInRight 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .right-side::before {
            content: '';
            position: absolute;
            left: -70px;
            top: 0;
            width: 140px;
            height: 100%;
            background: white;
            border-radius: 70% 0 0 70%;
            box-shadow: -20px 0 30px rgba(0,0,0,0.12);
            z-index: 1;
        }

        .login-form {
            width: 100%;
            max-width: 380px;
            position: relative;
            z-index: 2;
            padding: 0 20px;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .login-form h1 {
            color: #1a3a8f;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .login-form > p {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: calc(50% + 12px);
            transform: translateY(-50%);
            color: #1565C0;
            font-size: 15px;
            z-index: 5;
            pointer-events: none;
        }
        

        .form-control {
            background: #f0f4ff;
            border: 1.5px solid #e0e7ff;
            padding: 13px 16px 13px 40px;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1565C0;
            box-shadow: 0 0 0 4px rgba(21,101,192,0.12);
            background: #fff;
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 44px;
        }

        .toggle-pass {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #888;
            font-size: 15px;
            z-index: 5;
            transition: color 0.2s;
        }

        .toggle-pass:hover { color: #1565C0; }

        .btn-login {
            background: linear-gradient(135deg, #1565C0, #1976D2);
            color: white;
            border: none;
            padding: 14px;
            font-size: 15px;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            margin-top: 8px;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 0; height: 0;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.4s ease, height 0.4s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(21,101,192,0.4);
            color: white;
        }

        .btn-login:hover::after {
            width: 300px;
            height: 300px;
        }

        .btn-login:active { transform: translateY(0); }

        .form-check-label {
            font-size: 13px;
            color: #666;
        }

        .invalid-feedback {
            font-size: 12px;
            color: #dc3545;
            margin-top: 4px;
        }

        .alert {
            font-size: 13px;
            border-radius: 8px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- LEFT -->
    <div class="left-side">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <i class="fas fa-futbol float-icon"></i>
        <i class="fas fa-basketball float-icon"></i>
        <i class="fas fa-volleyball-ball float-icon"></i>
        <i class="fas fa-trophy float-icon"></i>

        <div class="left-content">
            <div class="logo">
                <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport Center">
            </div>
            <div class="tagline">
                Kelola lapangan dengan <span>mudah</span>,<br>
                layani pelanggan dengan <span>terbaik</span>!
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right-side">
        <div class="login-form">
            <h1>Selamat Datang Kembali!</h1>
            <p>Masuk ke akun admin untuk melanjutkan.</p>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-wrapper">
                    <label class="form-label">Username</label>
                    <div class="password-wrapper" style="position: relative;">
                        <i class="fas fa-user input-icon" style="top: 50%; transform: translateY(-50%);"></i>
                        <input type="text" name="username_admin"
                            class="form-control @error('username_admin') is-invalid @enderror"
                            placeholder="Masukkan username"
                            value="{{ old('username_admin') }}">
                    </div>
                    @error('username_admin')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-wrapper">
                    <label class="form-label">Password</label>
                    <div class="password-wrapper" style="position: relative;">
                        <i class="fas fa-lock input-icon" style="top: 50%; transform: translateY(-50%);"></i>
                        <input type="password" id="password" name="password_admin"
                            class="form-control @error('password_admin') is-invalid @enderror"
                            placeholder="Masukkan password">
                        <i class="fas fa-eye-slash toggle-pass" id="togglePassword"></i>
                    </div>
                    @error('password_admin')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login">LOGIN</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const password = document.getElementById('password');
        const show = password.type === 'password';
        password.type = show ? 'text' : 'password';
        this.classList.toggle('fa-eye', show);
        this.classList.toggle('fa-eye-slash', !show);
    });
</script>
</body>
</html>