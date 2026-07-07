<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bintang Sport Center - Sistem Booking Lapangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: #1a3a8f;
            font-size: 18px;
        }

        .navbar-brand img {
            height: 40px;
        }

        .nav-link {
            color: #444 !important;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-link:hover { color: #1565C0 !important; }

        .btn-demo {
            background: linear-gradient(135deg, #1565C0, #1976D2);
            color: white !important;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-demo:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(21,101,192,0.4);
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 50%, #f5f8ff 100%);
            display: flex;
            align-items: center;
            padding: 80px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(21,101,192,0.08) 0%, transparent 70%);
            top: -100px; right: -100px;
            animation: pulse 6s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1.2;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease both;
        }

        .hero-title span { color: #1565C0; }

        .hero-desc {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 36px;
            line-height: 1.7;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-btns {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #1565C0, #1976D2);
            color: white;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(21,101,192,0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(21,101,192,0.4);
            color: white;
        }

        .hero-stats {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 40px;
            animation: fadeInUp 0.8s ease 0.6s both;
        }

        .avatar-group {
            display: flex;
            align-items: center;
        }

        .avatar-group img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
            margin-left: -10px;
            flex-shrink: 0;
        }

        .avatar-group img:first-child {
            margin-left: 0;
        }

        .hero-stats-text strong {
            display: block;
            font-size: 18px;
            color: #1a1a2e;
        }

        .hero-stats-text span {
            font-size: 13px;
            color: #64748b;
        }

        .hero-image {
            animation: fadeInRight 0.8s ease 0.3s both;
            position: relative;
        }

        .hero-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .hero-image .badge-float {
            position: absolute;
            background: white;
            border-radius: 12px;
            padding: 12px 16px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: float 4s ease-in-out infinite;
        }

        .hero-image .badge-float.top { top: -20px; right: 20px; }
        .hero-image .badge-float.bottom { bottom: -20px; left: 20px; }

        .badge-float i { font-size: 20px; color: #1565C0; }
        .badge-float strong { display: block; font-size: 16px; color: #1a1a2e; }
        .badge-float span { font-size: 12px; color: #64748b; }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* FITUR */
        .section-fitur {
            padding: 100px 0;
            background: white;
        }

        .section-label {
            display: inline-block;
            background: #e8f0fe;
            color: #1565C0;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 16px;
        }

        .section-title span { color: #1565C0; }

        .section-desc {
            color: #64748b;
            font-size: 1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .fitur-card {
            background: #f8faff;
            border: 1px solid #e8f0fe;
            border-radius: 16px;
            padding: 32px 24px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }

        .fitur-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(21,101,192,0.12);
            border-color: #1565C0;
            background: white;
        }

        .fitur-icon {
            width: 70px; height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }

        .fitur-card h5 { font-size: 16px; font-weight: 700; color: #1a1a2e; margin-bottom: 10px; }
        .fitur-card p { font-size: 14px; color: #64748b; line-height: 1.6; margin: 0; }

        /* CARA KERJA */
        .section-cara {
            padding: 100px 0;
            background: #f8faff;
        }

        .step-item {
            text-align: center;
            position: relative;
        }

        .step-item::after {
            content: '';
            position: absolute;
            top: 35px;
            right: -50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #1565C0, #e8f0fe);
            z-index: 0;
        }

        .step-item:last-child::after { display: none; }

        .step-circle {
            width: 70px; height: 70px;
            border-radius: 50%;
            background: white;
            border: 3px solid #1565C0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 24px;
            color: #1565C0;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .step-item:hover .step-circle {
            background: #1565C0;
            color: white;
            transform: scale(1.1);
        }

        .step-num {
            position: absolute;
            top: -8px; right: -8px;
            width: 24px; height: 24px;
            background: #1565C0;
            color: white;
            border-radius: 50%;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .step-item h6 { font-weight: 700; color: #1a1a2e; margin-bottom: 8px; }
        .step-item p { font-size: 13px; color: #64748b; }

        /* CTA */
        .section-cta {
            padding: 60px 40px 80px;
            background: #f8faff;
        }

        .cta-inner {
            background: linear-gradient(135deg, #1a3a8f, #1565C0, #1976D2);
            border-radius: 24px;
            padding: 70px 40px;
            position: relative;
            overflow: hidden;
            max-width: 900px;
            margin: 0 auto;
        }

        .cta-inner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('/img/run.jpg') no-repeat center/cover;
            opacity: 0.1;
            z-index: 0;
        }

        .section-cta h2 { font-size: 2.5rem; font-weight: 800; color: white; margin-bottom: 12px; }
        .section-cta p { color: rgba(255,255,255,0.8); font-size: 1.1rem; margin-bottom: 32px; }
        .cta-btns { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }

        .btn-cta-white {
            background: white;
            color: #1565C0;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-cta-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        /* FOOTER */
        .footer {
            background: #0f1f4a;
            color: rgba(255,255,255,0.7);
            padding: 60px 0 30px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: white;
            font-size: 18px;
            margin-bottom: 16px;
        }

        .footer-brand img { height: 36px; }
        .footer p { font-size: 14px; line-height: 1.7; }
        .footer h6 { color: white; font-weight: 700; margin-bottom: 16px; }

        .footer a {
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            font-size: 14px;
            display: block;
            margin-bottom: 8px;
            transition: color 0.2s;
        }

        .footer a:hover { color: white; }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 24px;
            margin-top: 40px;
            font-size: 13px;
            text-align: center;
        }

        .social-links { display: flex; gap: 12px; margin-top: 20px; }

        .social-links a {
            width: 36px; height: 36px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white !important;
            font-size: 16px;
            transition: all 0.2s;
            margin: 0;
        }

        .social-links a:hover { background: #1565C0; }

        /* DEMO OVERLAY */
        .demo-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10,20,60,0.75);
            backdrop-filter: blur(8px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .demo-overlay.active {
            display: flex;
        }

        .demo-modal {
            background: white;
            border-radius: 24px;
            padding: 44px 36px;
            max-width: 480px;
            width: 90%;
            text-align: center;
            position: relative;
            box-shadow: 0 24px 80px rgba(0,0,0,0.3);
            animation: fadeInUp 0.3s ease both;
        }

        .demo-close {
            position: absolute;
            top: 16px; right: 20px;
            background: none;
            border: none;
            font-size: 22px;
            color: #94a3b8;
            cursor: pointer;
            line-height: 1;
            transition: color 0.2s;
        }

        .demo-close:hover { color: #1a1a2e; }

        .demo-icon {
            width: 64px; height: 64px;
            background: #e8f0fe;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
            color: #1565C0;
        }

        .demo-modal h4 {
            font-weight: 800;
            color: #1a1a2e;
            margin-bottom: 8px;
            font-size: 1.4rem;
        }

        .demo-modal > p {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 28px;
        }

        .demo-options {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .demo-option {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 18px 20px;
            border: 2px solid #e8f0fe;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.25s ease;
            text-align: left;
        }

        .demo-option:hover {
            border-color: #1565C0;
            background: #f0f4ff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(21,101,192,0.12);
        }

        .demo-option-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .demo-option-icon.pelanggan {
            background: #e8f0fe;
            color: #1565C0;
        }

        .demo-option-icon.admin {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .demo-option-text strong {
            display: block;
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 3px;
        }

        .demo-option-text span {
            font-size: 13px;
            color: #64748b;
        }

        .demo-option-arrow {
            margin-left: auto;
            color: #94a3b8;
            font-size: 14px;
            transition: transform 0.2s;
        }

        .demo-option:hover .demo-option-arrow {
            transform: translateX(4px);
            color: #1565C0;
        }

        @keyframes fadeInUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeInRight {
            from { transform: translateX(30px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>

<!-- DEMO OVERLAY -->
<div class="demo-overlay" id="demoOverlay" onclick="handleOverlayClick(event)">
    <div class="demo-modal">
        <button class="demo-close" onclick="closeDemo()">&times;</button>
        <div class="demo-icon">
            <i class="fas fa-play-circle"></i>
        </div>
        <h4>Masuk Sebagai</h4>
        <p>Pilih mode untuk mencoba sistem Bintang Sport Center</p>
        <div class="demo-options">
            <a href="{{ route('jadwal.public') }}" class="demo-option">
                <div class="demo-option-icon pelanggan">
                    <i class="fas fa-user"></i>
                </div>
                <div class="demo-option-text">
                    <strong>Pelanggan</strong>
                    <span>Lihat jadwal & booking lapangan</span>
                </div>
                <i class="fas fa-chevron-right demo-option-arrow"></i>
            </a>
            <a href="{{ route('login') }}" class="demo-option">
                <div class="demo-option-icon admin">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="demo-option-text">
                    <strong>Admin</strong>
                    <span>Kelola lapangan, booking & laporan</span>
                </div>
                <i class="fas fa-chevron-right demo-option-arrow"></i>
            </a>
        </div>
    </div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport Center">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto gap-2 me-3">
                <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="#cara-kerja">Cara Kerja</a></li>
                <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
            </ul>
            <div class="d-flex gap-2">
                <button onclick="openDemo()" class="btn-demo">
                    <i class="fas fa-play-circle me-1"></i> Demo
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="section-label">SISTEM INFORMASI MANAJEMEN</div>
                <h1 class="hero-title">
                    Sistem Booking Lapangan <span>Olahraga</span> Berbasis Web
                </h1>
                <p class="hero-desc">
                    Solusi pemesanan lapangan yang mudah, cepat, dan terintegrasi untuk pelanggan maupun admin Bintang Sport Center.
                </p>
                <div class="hero-btns">
                    <button onclick="openDemo()" class="btn-primary-custom">
                        <i class="fas fa-play-circle"></i> Coba Demo
                    </button>
                </div>
                <div class="hero-stats">
                    <div class="avatar-group">
                        <img src="{{ asset('img/avatar-1.jpg') }}" alt="">
                        <img src="{{ asset('img/avatar-2.jpg') }}" alt="">
                        <img src="{{ asset('img/avatar-3.jpg') }}" alt="">
                    </div>
                    <div class="hero-stats-text">
                        <strong>1000+</strong>
                        <span>Pelanggan aktif</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="{{ asset('img/potret-aktivitas.png') }}" alt="Aktivitas Olahraga">
                    <div class="badge-float top">
                        <i class="fas fa-calendar-check"></i>
                        <div>
                            <strong>1.250</strong>
                            <span>Total Booking</span>
                        </div>
                    </div>
                    <div class="badge-float bottom">
                        <i class="fas fa-qrcode"></i>
                        <div>
                            <strong>QRIS</strong>
                            <span>Pembayaran mudah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FITUR -->
<section class="section-fitur" id="fitur">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">FITUR UNGGULAN</div>
            <h2 class="section-title">Semua yang Anda Butuhkan, <span>dalam Satu Platform</span></h2>
            <p class="section-desc">Dirancang untuk memudahkan pengelolaan lapangan dan pengalaman booking pelanggan.</p>
        </div>
        <div class="row g-4 mt-2">
            <div class="col-md-6 col-lg-3">
                <div class="fitur-card">
                    <div class="fitur-icon" style="background:#e8f0fe;">
                        <i class="fas fa-calendar-alt" style="color:#1565C0;"></i>
                    </div>
                    <h5>Jadwal Real-time</h5>
                    <p>Lihat ketersediaan lapangan secara real-time. Tersedia, penuh, dan promo ditandai dengan jelas.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="fitur-card">
                    <div class="fitur-icon" style="background:#e8f5e9;">
                        <i class="fas fa-clipboard-check" style="color:#2e7d32;"></i>
                    </div>
                    <h5>Booking Online</h5>
                    <p>Pesan lapangan tanpa perlu membuat akun. Proses cepat, praktis, dan mudah kapan saja dimana saja.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="fitur-card">
                    <div class="fitur-icon" style="background:#f3e5f5;">
                        <i class="fas fa-qrcode" style="color:#7b1fa2;"></i>
                    </div>
                    <h5>Pembayaran QRIS</h5>
                    <p>Bayar lebih mudah dan aman menggunakan QRIS yang terintegrasi dengan payment gateway resmi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="fitur-card">
                    <div class="fitur-icon" style="background:#e8f5e9;">
                        <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                    </div>
                    <h5>Status & Notifikasi</h5>
                    <p>Cek status booking dan terima konfirmasi otomatis melalui WhatsApp setelah pembayaran berhasil.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon" style="background:#fff3e0;">
                        <i class="fas fa-percent" style="color:#e65100;"></i>
                    </div>
                    <h5>Promo Otomatis</h5>
                    <p>Promo dan diskon berlaku otomatis sesuai periode dan lapangan yang dipilih.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon" style="background:#e8f0fe;">
                        <i class="fas fa-tachometer-alt" style="color:#1565C0;"></i>
                    </div>
                    <h5>Dashboard Admin</h5>
                    <p>Kelola lapangan, promo, jadwal, dan booking dalam satu dashboard yang terintegrasi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon" style="background:#fce4ec;">
                        <i class="fas fa-chart-pie" style="color:#c62828;"></i>
                    </div>
                    <h5>Rekap & Export Laporan</h5>
                    <p>Lihat laporan rekapitulasi harian maupun bulanan dan export ke PDF dengan mudah.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CARA KERJA -->
<section class="section-cara" id="cara-kerja">
    <div class="container">
        <div class="text-center mb-5">
            <div class="section-label">CARA KERJA SISTEM</div>
            <h2 class="section-title">Mudah dalam <span>5 Langkah</span></h2>
        </div>
        <div class="row g-4">
            <div class="col step-item">
                <div class="step-circle">
                    <i class="fas fa-calendar"></i>
                    <span class="step-num">1</span>
                </div>
                <h6>Pilih Jadwal</h6>
                <p>Pilih lapangan, tanggal, dan jam yang tersedia.</p>
            </div>
            <div class="col step-item">
                <div class="step-circle">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="step-num">2</span>
                </div>
                <h6>Booking</h6>
                <p>Isi data diri secara lengkap dan konfirmasi pesanan.</p>
            </div>
            <div class="col step-item">
                <div class="step-circle">
                    <i class="fas fa-qrcode"></i>
                    <span class="step-num">3</span>
                </div>
                <h6>Bayar QRIS</h6>
                <p>Lakukan pembayaran dengan memindai kode QRIS.</p>
            </div>
            <div class="col step-item">
                <div class="step-circle">
                    <i class="fab fa-whatsapp"></i>
                    <span class="step-num">4</span>
                </div>
                <h6>Terima Konfirmasi</h6>
                <p>Dapatkan konfirmasi booking otomatis via WhatsApp.</p>
            </div>
            <div class="col step-item">
                <div class="step-circle">
                    <i class="fas fa-check-circle"></i>
                    <span class="step-num">5</span>
                </div>
                <h6>Selesai</h6>
                <p>Booking berhasil! Siap bermain.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section-cta" id="tentang">
    <div class="cta-inner text-center">
        <div style="position:relative; z-index:1;">
            <h2>Siap Mencoba Sistem?</h2>
            <p>Rasakan kemudahan booking lapangan dalam hitungan detik.</p>
            <div class="cta-btns">
                <button onclick="openDemo()" class="btn-cta-white">
                    <i class="fas fa-play-circle"></i> Coba Demo
                </button>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="footer-brand">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo">
                </div>
                <p>Sistem booking lapangan olahraga berbasis web yang mudah, cepat, dan terpercaya.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <div class="col-lg-2 offset-lg-2">
                <h6>Menu</h6>
                <a href="#fitur">Fitur</a>
                <a href="#cara-kerja">Cara Kerja</a>
                <a href="#tentang">Tentang</a>
            </div>
            <div class="col-lg-4">
                <h6>Kontak</h6>
                <a href="#"><i class="fas fa-phone me-2"></i>0812-3456-7890</a>
                <a href="#"><i class="fas fa-envelope me-2"></i>info@bintangsic.id</a>
                <a href="#"><i class="fas fa-map-marker-alt me-2"></i>Gresik, Jawa Timur</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 Bintang Sport Center. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openDemo() {
        document.getElementById('demoOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDemo() {
        document.getElementById('demoOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }

    function handleOverlayClick(e) {
        if (e.target === document.getElementById('demoOverlay')) {
            closeDemo();
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDemo();
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) target.scrollIntoView({ behavior: 'smooth' });
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.6s ease both';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fitur-card, .step-item').forEach(el => observer.observe(el));
</script>
</body>
</html>