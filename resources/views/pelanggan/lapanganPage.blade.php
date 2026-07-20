<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Lapangan - Bintang Sport Center</title>

    <!-- Icon CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

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
        }

        .navbar .btn-nav:hover {
            background: #0348b9;
        }

        /* HALAMAN LAPANGAN */
        .lapangan-page {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 115px 55px 40px;
            flex: 1;
        }

        .page-title {
            font-size: 31px;
            font-weight: 700;
            color: #071f56;
            margin-bottom: 5px;
        }

        .page-subtitle {
            font-size: 16px;
            color: #7b8494;
            font-weight: 600;
            margin-bottom: 45px;
        }

        .lapangan-grid {
            display: grid;
            grid-template-columns: repeat(2, 450px);
            justify-content: center;
            gap: 65px 90px;
        }

        .lapangan-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.18);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .lapangan-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(0, 82, 204, 0.18);
        }

        .lapangan-card .lapangan-image {
            width: 100%;
            height: 214px;
            display: block;
            object-fit: cover;
        }

        .lapangan-content {
            padding: 18px 20px 26px;
        }

        .lapangan-name {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 27px;
            color: #071f56;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .lapangan-name i {
            color: #0756d9;
            font-size: 24px;
        }

        .icon-badminton {
            width: 25px;
            height: 25px;
            object-fit: contain;
        }

        .lapangan-price {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #0046b8;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 17px;
        }

        .lapangan-price i {
            font-size: 18px;
        }

        .fasilitas-label {
            color: #777;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 11px;
        }

        .fasilitas-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .fasilitas-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border: 1px solid #c6d9f7;
            border-radius: 8px;
            background: #f7faff;
            color: #10275b;
            font-size: 13px;
            font-weight: 600;
        }

        .fasilitas-item i {
            color: #10275b;
            font-size: 13px;
        }

        .btn-lihat-jadwal {
            display: block;
            width: 100%;
            padding: 11px 15px;
            border-radius: 8px;
            background: #0756d9;
            color: #fff;
            text-align: center;
            font-size: 18px;
            font-weight: 650;
            transition: 0.2s;
        }

        .btn-lihat-jadwal:hover {
            background: #0348b9;
        }

        /* FOOTER */
        .footer {
            margin-top: 40px;
            background: #031d55;
            color: white;
        }

        .footer-main {
            max-width: 1100px;
            margin: auto;
            padding: 55px 60px 38px;
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

        @media (max-width: 850px) {
            .navbar {
                padding: 14px 30px;
            }

            .navbar nav {
                gap: 18px;
            }

            .lapangan-page {
                padding-left: 30px;
                padding-right: 30px;
            }

            .lapangan-grid {
                gap: 30px;
            }
        }

        @media (max-width: 650px) {
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

            .lapangan-page {
                padding: 95px 20px 35px;
            }

            .page-title {
                font-size: 26px;
            }

            .page-subtitle {
                font-size: 14px;
                margin-bottom: 30px;
            }

            .lapangan-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .lapangan-card .lapangan-image {
                height: 200px;
            }

            .lapangan-name {
                font-size: 23px;
            }

            .footer-main {
                padding: 45px 25px;
                grid-template-columns: 1fr;
                gap: 30px;
            }
        }
    </style>
</head>

<body>

    <header class="navbar">
        <a href="{{ url('/beranda') }}" class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport">
        </a>

        <nav>
            <a href="{{ url('/beranda') }}">Beranda</a>
            <a href="{{ url('/lapangan') }}" class="active">Lapangan</a>
            <a href="{{ route('jadwal.public') }}">Jadwal</a>
            <a href="{{ route('booking.cek') }}">Riwayat</a>
        </nav>

        <a href="{{ route('jadwal.public') }}" class="btn-nav">
            Booking Sekarang
        </a>
    </header>

    <main class="lapangan-page">
        <h1 class="page-title">Lapangan Terbaik</h1>

        <p class="page-subtitle">
            Temukan fasilitas olahraga premium dengan standar internasional.
        </p>

        <section class="lapangan-grid">

            <!-- Futsal A -->
            <article class="lapangan-card">
                <img
                    src="{{ asset('img/futsal-a01.png') }}"
                    alt="Futsal A"
                    class="lapangan-image"
                >

                <div class="lapangan-content">
                    <h2 class="lapangan-name">
                        <i class="fa-solid fa-futbol"></i>
                        Futsal A
                    </h2>

                    <p class="lapangan-price">
                        <i class="fa-solid fa-money-bills"></i>
                        Rp {{ number_format($jenisLapangans['Futsal A']->harga_per_jam ?? 0,0,',','.') }} / Jam
                    </p>

                    <p class="fasilitas-label">Fasilitas :</p>

                    <div class="fasilitas-list">
                        <span class="fasilitas-item">
                            <i class="fa-regular fa-circle-check"></i>
                            Lantai Interlock
                        </span>

                        <span class="fasilitas-item">
                            <i class="fa-regular fa-circle-check"></i>
                            28 x 15 Meter
                        </span>
                    </div>

                   <a href="{{ route('jadwal.public', ['kategori' => 'Futsal A']) }}" class="btn-lihat-jadwal">Lihat jadwal</a>
                </div>
            </article>

            <!-- Futsal B -->
            <article class="lapangan-card">
                <img
                    src="{{ asset('img/futsal-b.png') }}"
                    alt="Futsal B"
                    class="lapangan-image"
                >

                <div class="lapangan-content">
                    <h2 class="lapangan-name">
                        <i class="fa-solid fa-futbol"></i>
                        Futsal B
                    </h2>

                    <p class="lapangan-price">
                        <i class="fa-solid fa-money-bills"></i>
                        Rp {{ number_format($jenisLapangans['Futsal B']->harga_per_jam ?? 0,0,',','.') }} / Jam
                    </p>

                    <p class="fasilitas-label">Fasilitas :</p>

                    <div class="fasilitas-list">
                        <span class="fasilitas-item">
                            <i class="fa-regular fa-circle-check"></i>
                            Lantai Vinyl Pro
                        </span>

                        <span class="fasilitas-item">
                            <i class="fa-regular fa-circle-check"></i>
                            25 x 13 Meter
                        </span>
                    </div>

                    <a href="{{ route('jadwal.public', ['kategori' => 'Futsal B']) }}" class="btn-lihat-jadwal">Lihat jadwal</a>
                </div>
            </article>

            <!-- Badminton -->
            <article class="lapangan-card">
                <img
                    src="{{ asset('img/badminton.png') }}"
                    alt="Badminton"
                    class="lapangan-image"
                >

                <div class="lapangan-content">
                    <h2 class="lapangan-name">
                        <img
                            src="{{ asset('img/icon-badminton.png') }}"
                            alt="Icon Badminton"
                            class="icon-badminton"
                        >
                        Badminton
                    </h2>

                    <p class="lapangan-price">
                        <i class="fa-solid fa-money-bills"></i>
                        Rp {{ number_format($jenisLapangans['Badminton']->harga_per_jam ?? 0,0,',','.') }} / Jam
                    </p>

                    <p class="fasilitas-label">Fasilitas :</p>

                    <div class="fasilitas-list">
                        <span class="fasilitas-item">
                            <i class="fa-regular fa-circle-check"></i>
                            Rubber Mat Yonex
                        </span>

                        <span class="fasilitas-item">
                            <i class="fa-regular fa-circle-check"></i>
                            13,4 x 6,1 Meter
                        </span>
                    </div>

                    <a href="{{ route('jadwal.public', ['kategori' => 'Badminton']) }}" class="btn-lihat-jadwal">Lihat jadwal</a>
                </div>
            </article>

            <!-- Basket -->
            <article class="lapangan-card">
                <img
                    src="{{ asset('img/basket.png') }}"
                    alt="Basket"
                    class="lapangan-image"
                >

                <div class="lapangan-content">
                    <h2 class="lapangan-name">
                        <i class="fa-solid fa-basketball"></i>
                        Basket
                    </h2>

                    <p class="lapangan-price">
                        <i class="fa-solid fa-money-bills"></i>
                        Rp {{ number_format($jenisLapangans['Basket']->harga_per_jam ?? 0,0,',','.') }} / Jam
                    </p>

                    <p class="fasilitas-label">Fasilitas :</p>

                    <div class="fasilitas-list">
                        <span class="fasilitas-item">
                            <i class="fa-regular fa-circle-check"></i>
                            Lantai Vinyl Pro
                        </span>

                        <span class="fasilitas-item">
                            <i class="fa-regular fa-circle-check"></i>
                            28 x 15 Meter
                        </span>
                    </div>

                    <a href="{{ route('jadwal.public', ['kategori' => 'Futsal A']) }}" class="btn-lihat-jadwal">Lihat Jadwal</a>
                </div>
            </article>
        </section>
    </main>

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

</body>
</html>