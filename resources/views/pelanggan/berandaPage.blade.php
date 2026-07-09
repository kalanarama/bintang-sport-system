<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Bintang Sport Center</title>

    <!-- Icon CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />

    <!-- Icon Bootsrap -->
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fa;
            color: #10275b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* NAVBAR */
        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            padding: 14px 60px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .navbar .logo img {
            height: 44px;
            width: auto;
            display: block;
        }

        .navbar nav {
            display: flex;
            gap: 40px;
        }

        .navbar nav a {
            font-weight: 600;
            font-size: 15px;
            color: #475569;
        }

        .navbar nav a:hover,
        .navbar nav a.active {
            color: #0052cc;
        }

        .navbar .btn-nav {
            background: #0756d9;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 22px;
            border-radius: 24px;
            white-space: nowrap;
            transition: 0.2s;
            box-shadow: 0 5px 11px rgba(7, 86, 217, 0.28);
        }

        .navbar .btn-nav:hover {
            background: #0348b9;
        }

        /* HERO */
        .hero {
            min-height: 520px;
            margin-top: 72px;
            background:
                linear-gradient(90deg, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.72) 40%, rgba(0,0,0,0.05) 100%),
                url("{{ asset('img/hero.png') }}");
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
        }

        .hero-content {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 60px;
            color: white;
        }

        .hero h1 {
            font-size: 38px;
            line-height: 1.25;
            max-width: 520px;
            margin-bottom: 14px;
        }

        .hero p {
            font-size: 17px;
            font-weight: 500;
            line-height: 1.45;
            max-width: 430px;
            margin-bottom: 25px;
        }

        .booking-step-box {
            display: flex;
            align-items: center;
            gap: 20px;
            width: fit-content;
            padding: 15px 18px;
            margin-bottom: 34px;
            border-radius: 7px;
            background: #f6f9ff;
            color: #10275b;
        }

        .booking-step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 650;
        }

        .booking-step i {
            font-size: 27px;
            color: #0756d9;
        }

        .btn-hero {
            display: inline-block;
            background: #0756d9;
            color: white;
            padding: 13px 25px;
            border-radius: 7px;
            font-size: 15px;
            font-weight: 650;
        }

        .btn-hero:hover {
            background: #0348b9;
        }

        /* SECTION LAPANGAN */
        .section {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 100px 60px 30px;
        }

        .section-title {
            text-align: center;
            color: #111827;
            font-size: 27px;
            margin-bottom: 10px;
        }

        .section-subtitle {
            max-width: 560px;
            margin: 0 auto 52px;
            text-align: center;
            color: #7b8494;
            font-size: 15px;
            line-height: 1.4;
        }

        .lapangan-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .lapangan-card {
            padding: 12px;
            background: white;
            border: 1px solid #d9e7ff;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 82, 204, 0.12);
            transition: 0.2s;
        }

        .lapangan-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 9px 20px rgba(0, 82, 204, 0.18);
        }

        .lapangan-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 7px;
        }

        .lapangan-card h3 {
            color: #10275b;
            font-size: 15px;
            margin: 12px 0 8px;
        }

        .lapangan-card h3 img {
            width: 15px;
            height: 15px;
            margin-right: 5px;
        }

        .lapangan-card h3 i {
            color: #0756d9;
            margin-right: 5px;
        }

        .lapangan-card p {
            color: #737b8c;
            font-size: 13px;
            line-height: 1.35;
            min-height: 45px;
            margin-bottom: 13px;
        }

        .btn-jadwal {
            display: block;
            background: #0756d9;
            color: white;
            text-align: center;
            padding: 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .btn-jadwal:hover {
            background: #0348b9;
        }

        /* KEUNGGULAN */
        .keunggulan {
            max-width: 1200px;
            width: 100%;
            margin: 30px auto;
            padding: 90px 60px 20px;
        }

        .feature-list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 35px;
            margin-top: 45px;
        }

        .feature {
            text-align: center;
        }

        .feature-icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: #0756d9;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 25px;
            box-shadow: 0 5px 11px rgba(7, 86, 217, 0.28);
        }

        .feature h3 {
            font-size: 15px;
            margin-bottom: 8px;
        }

        .feature p {
            color: #737b8c;
            font-size: 12px;
            line-height: 1.35;
        }

        /* CTA */
        .cta {
            max-width: 760px;
            margin: 150px auto 0;
            padding: 70px 25px;
            text-align: center;
            background: #061f58;
            border-radius: 22px;
            color: white;
            box-shadow: 0 6px 18px rgba(6, 31, 88, 0.2);
        }

        .cta h2 {
            font-size: 25px;
            margin-bottom: 10px;
        }

        .cta p {
            font-size: 13px;
            line-height: 1.45;
            margin-bottom: 24px;
        }

        .cta a {
            display: inline-block;
            padding: 11px 26px;
            border-radius: 7px;
            background: #0756d9;
            font-size: 13px;
            font-weight: 600;
        }

        .cta a:hover {
            background: #0348b9;
        }

        /* FOOTER */
        .footer {
            margin-top: 90px;
            background: #031d55;
            color: white;
        }

        .footer-main {
            max-width: 1200px;
            margin: auto;
            padding: 55px 60px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 60px;
        }

        .footer-logo {
            width: 190px;
            margin-bottom: 18px;
        }

        .footer-desc {
            max-width: 290px;
            color: #b8c5e2;
            font-size: 13px;
            line-height: 1.4;
        }

        .footer h3 {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .footer ul {
            list-style: none;
        }

        .footer li {
            margin-bottom: 14px;
        }

        .footer li a {
            color: #b8c5e2;
            font-size: 13px;
        }

        .footer li a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.15);
            text-align: center;
            padding: 18px;
            color: #b8c5e2;
            font-size: 12px;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .lapangan-list,
            .feature-list {
                grid-template-columns: repeat(2, 1fr);
            }

            .navbar {
                padding: 14px 30px;
            }

            .navbar nav {
                gap: 18px;
            }
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 12px 18px;
            }

            .navbar nav {
                display: none;
            }

            .navbar .logo img {
                height: 36px;
            }

            .navbar .btn-nav {
                padding: 9px 14px;
                font-size: 12px;
            }

            .hero {
                min-height: 520px;
            }

            .hero-content,
            .section,
            .keunggulan {
                padding-left: 20px;
                padding-right: 20px;
            }

            .hero h1 {
                font-size: 29px;
            }

            .booking-step-box {
                gap: 10px;
                padding: 13px 10px;
            }

            .booking-step {
                font-size: 10px;
                gap: 4px;
            }

            .booking-step i {
                font-size: 20px;
            }

            .lapangan-list,
            .feature-list {
                grid-template-columns: 1fr;
            }

            .footer-main {
                padding: 45px 25px;
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }

        /* Animasi muncul saat discroll */
        .reveal {
            opacity: 0;
            transform: translateY(45px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }

        .lapangan-card:nth-child(2) {
            transition-delay: 0.15s;
        }

        .lapangan-card:nth-child(3) {
            transition-delay: 0.3s;
        }

        .feature:nth-child(4) {
            transition-delay: 0.45s;
        }

        .feature:nth-child(2) {
            transition-delay: 0.15s;
        }

        .feature:nth-child(3) {
            transition-delay: 0.3s;
        }

        .feature:nth-child(4) {
            transition-delay: 0.45s;
        }
    </style>
</head>

<body>

    <header class="navbar">
        <a href="{{ url('/beranda') }}" class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport">
        </a>

        <nav>
            <a href="{{ url('/beranda') }}" class="active">Beranda</a>
            <a href="{{ url('/lapangan') }}">Lapangan</a>
            <a href="{{ route('jadwal.public') }}">Jadwal</a>
            <a href="{{ route('booking.cek') }}">Riwayat</a>
        </nav>

        <a href="{{ route('jadwal.public') }}" class="btn-nav">
            Booking Sekarang
        </a>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Booking Lapangan<br>Olahraga Jadi Lebih Mudah</h1>

                <p>
                    Booking lapangan olahragamu, cek ketersediaan,
                    dan booking online sekarang!
                </p>

                <div class="booking-step-box">
                    <div class="booking-step">
                        <i class="fa-solid fa-futbol"></i>
                        <span>Pilih Lapangan</span>
                    </div>

                    <div class="booking-step">
                        <i class="fa-solid fa-calendar"></i>
                        <span>Pilih Tanggal</span>
                    </div>

                    <div class="booking-step">
                        <i class="fa-solid fa-clock"></i>
                        <span>Pilih Waktu</span>
                    </div>
                </div>

                <a href="{{ route('jadwal.public') }}" class="btn-hero">
                    Booking Sekarang <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>

        <!-- Lapangan Section -->
        <section class="section reveal" id="lapangan">
            <h2 class="section-title">Pilih Lapangan Favoritmu</h2>

            <p class="section-subtitle">
                Kami menyediakan berbagai pilihan fasilitas olahraga premium dengan
                standar kualitas terbaik untuk kenyamanan Anda.
            </p>

            <div class="lapangan-list">
                <article class="lapangan-card reveal">
                    <img src="{{ asset('img/futsal-a01.png') }}" alt="Futsal A">
                    <h3><i class="fa-regular fa-futbol"></i> Futsal A</h3>
                    <p>Lapangan rumput sintetis berkualitas dengan pencahayaan indoor yang terang.</p>
                    <a href="{{ url('/lapangan') }}" class="btn-jadwal">Selengkapnya</a>
                </article>

                <article class="lapangan-card reveal">
                    <img src="{{ asset('img/futsal-b.png') }}" alt="Futsal B">
                    <h3><i class="fa-regular fa-futbol"></i> Futsal B</h3>
                    <p>Lapangan indoor dengan suasana yang nyaman dan lapangan yang terawat.</p>
                    <a href="{{ url('/lapangan') }}" class="btn-jadwal">Selengkapnya</a>
                </article>

                <article class="lapangan-card reveal">
                    <img src="{{ asset('img/badminton.png') }}" alt="Badminton">
                    <h3>
                        <img src="{{ asset('img/icon-badminton.png') }}"
                        alt="Icon Badminton" class="icon-badminton">
                        Badminton
                    </h3>
                    <p>Lapangan badminton yang terawat dengan net standar berkualitas.</p>
                    <a href="{{ url('/lapangan') }}" class="btn-jadwal">Selengkapnya</a>
                </article>

                <article class="lapangan-card reveal">
                    <img src="{{ asset('img/basket.png') }}" alt="Basket">
                    <h3><i class="fa-solid fa-basketball"></i> Basket</h3>
                    <p>Lapangan basket indoor yang nyaman dengan garis lapangan yang jelas.</p>
                    <a href="{{ url('/lapangan') }}" class="btn-jadwal">Selengkapnya</a>
                </article>
            </div>
        </section>

        <!-- Keunggulan Section -->
        <section class="keunggulan reveal">
            <h2 class="section-title">Kenapa Memilih Bintang Sport Center?</h2>

            <p class="section-subtitle">
                Karena kami menyediakan tempat olahraga nyaman dengan fasilitas lengkap
                dan pelayanan yang baik.
            </p>

            <div class="feature-list">
                <div class="feature reveal">
                    <div class="feature-icon"><i class="bi bi-bell-fill"></i></div>
                    <h3>Fasilitas Lengkap</h3>
                    <p>Nikmati berbagai fasilitas olahraga yang lengkap, nyaman, dan terawat.</p>
                </div>

                <div class="feature reveal">
                    <div class="feature-icon"><i class="bi bi-lightning-charge-fill"></i></div>
                    <h3>Konfirmasi Cepat</h3>
                    <p>Sistem otomatis memastikan booking Anda dikonfirmasi secara instan.</p>
                </div>

                <div class="feature reveal">
                    <div class="feature-icon"><i class="bi bi-shield-fill"></i></div>
                    <h3>Pembayaran Aman</h3>
                    <p>Berbagai metode pembayaran yang terenkripsi dan aman untuk transaksi.</p>
                </div>

                <div class="feature reveal">
                    <div class="feature-icon"><i class="bi bi-clock-fill"></i></div>
                    <h3>Jadwal Real-time</h3>
                    <p>Informasi ketersediaan lapangan selalu diperbarui secara langsung.</p>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="cta reveal">
                <h2>Sudah Siap Bertanding Hari Ini?</h2>
                <p>
                    Amankan slot waktu favorit tim Anda sebelum kehabisan.<br>
                    Proses cepat, mudah, dan terpercaya.
                </p>

                <a href="{{ route('jadwal.public') }}">
                    Booking Lapangan Sekarang
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main">
            <div>
                <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport" class="footer-logo">

                <p class="footer-desc">
                    Lapangan olahraga dengan fasilitas dan kenyamanan yang berkualitas
                    untuk mendukung gaya hidup sehat Anda.
                </p>
            </div>

            <div>
                <h3>Menu Cepat</h3>
                <ul>
                    <li><a href="{{ url('/beranda') }}">Beranda</a></li>
                    <li><a href="{{ url('/lapangan') }}">Lapangan</a></li>
                    <li><a href="{{ route('jadwal.public') }}">Jadwal</a></li>
                    <li><a href="{{ route('booking.cek') }}">Booking Saya</a></li>
                </ul>
            </div>

            <div>
                <h3>Bantuan</h3>
                <ul>
                    <li><a href="{{ route('kebijakan') }}">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('syarat') }}">Syarat & Ketentuan</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            © 2026 Bintang Sport Center. All rights reserved.
        </div>
    </footer>

    <script>
        const revealElements = document.querySelectorAll('.reveal');

        function revealOnScroll() {
            revealElements.forEach(function(element) {
                const posisiElement = element.getBoundingClientRect().top;
                const tinggiLayar = window.innerHeight;

                if (posisiElement < tinggiLayar - 100) {
                    element.classList.add('show');
                }
            });
        }

        window.addEventListener('scroll', revealOnScroll);

        // Supaya elemen yang sudah terlihat saat halaman pertama dibuka langsung muncul
        revealOnScroll();
    </script>
</body>
</html>