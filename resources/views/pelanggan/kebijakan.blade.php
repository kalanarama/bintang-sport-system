<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kebijakan Privasi - Bintang Sport Center</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fa;
            color: #10275b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        a { text-decoration: none; color: inherit; }
        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            padding: 14px 60px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
        }
        .navbar .logo img { height: 44px; width: auto; display: block; }
        .navbar nav { display: flex; gap: 40px; }
        .navbar nav a { font-weight: 600; font-size: 15px; color: #475569; }
        .navbar nav a:hover, .navbar nav a.active { color: #0052cc; }
        .navbar .btn-nav {
            background: #0756d9;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 10px 22px;
            border-radius: 24px;
            white-space: nowrap;
            transition: 0.2s;
            box-shadow: 0 5px 11px rgba(7,86,217,0.28);
        }
        .navbar .btn-nav:hover { background: #0348b9; }

        .container {
            max-width: 800px;
            margin: 105px auto 40px;
            padding: 0 20px;
            flex: 1;
        }
        .page-header {
            margin-bottom: 28px;
        }
        .page-header h1 {
            font-size: 32px;
            font-weight: 800;
            color: #03045e;
            margin-bottom: 8px;
        }
        .page-header p {
            color: #475569;
            font-size: 15px;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        .card h2 {
            font-size: 18px;
            font-weight: 700;
            color: #03045e;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card h2::before {
            content: '';
            width: 4px;
            height: 20px;
            background: #0052cc;
            border-radius: 2px;
            display: inline-block;
        }
        .card p {
            color: #475569;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 12px;
        }
        .card p:last-child { margin-bottom: 0; }
        .card ul {
            padding-left: 20px;
            color: #475569;
            font-size: 15px;
            line-height: 2;
        }
        .last-updated {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 24px;
        }

        .footer {
            background: #03045e;
            color: #f4f7fa;
            padding: 50px 40px 30px;
            margin-top: 60px;
        }
        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            gap: 30px;
        }
        .footer .brand-logo img { height: 60px; margin-bottom: 12px; }
        .footer .tagline { font-size: 14px; color: #cbd5e1; max-width: 380px; }
        .footer h3 { font-size: 18px; font-weight: 700; color: white; margin-bottom: 16px; }
        .footer ul { list-style: none; }
        .footer ul li { margin-bottom: 12px; }
        .footer ul li a { color: #cbd5e1; font-size: 15px; transition: color 0.2s; }
        .footer ul li a:hover { color: white; }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.15);
            margin-top: 40px;
            padding-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #cbd5e1;
        }

        @media (max-width: 700px) {
            .navbar { padding: 14px 20px; }
            .navbar nav { display: none; }
            .card { padding: 24px 16px; }
            .footer-inner { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="{{ url('/beranda') }}" class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport" />
        </a>
        <nav>
            <a href="{{ url('/beranda') }}">Beranda</a>
            <a href="{{ url('/lapangan') }}">Lapangan</a>
            <a href="{{ route('jadwal.public') }}">Jadwal</a>
            <a href="{{ route('booking.cek') }}">Riwayat</a>
        </nav>
        <a href="{{ route('jadwal.public') }}" class="btn-nav">Booking Sekarang</a>
    </div>

    <div class="container">
        <div class="page-header">
            <h1>Kebijakan Privasi</h1>
            <p class="last-updated">Terakhir diperbarui: 1 Juli 2026</p>
            <p>Kami di Bintang Sport Center berkomitmen untuk melindungi privasi dan keamanan data pribadi Anda.</p>
        </div>

        <div class="card">
            <h2>Informasi yang Kami Kumpulkan</h2>
            <p>Kami mengumpulkan informasi yang Anda berikan secara langsung saat melakukan pemesanan lapangan, meliputi:</p>
            <ul>
                <li>Nama lengkap</li>
                <li>Nomor WhatsApp/HP</li>
                <li>Data transaksi pembayaran</li>
                <li>Riwayat pemesanan lapangan</li>
            </ul>
        </div>

        <div class="card">
            <h2>Penggunaan Informasi</h2>
            <p>Informasi yang kami kumpulkan digunakan untuk:</p>
            <ul>
                <li>Memproses dan mengkonfirmasi pemesanan lapangan Anda</li>
                <li>Mengirimkan notifikasi konfirmasi booking melalui WhatsApp</li>
                <li>Memberikan layanan pelanggan yang lebih baik</li>
                <li>Mengelola riwayat transaksi Anda</li>
            </ul>
        </div>

        <div class="card">
            <h2>Keamanan Data</h2>
            <p>Kami menggunakan teknologi enkripsi untuk melindungi data pribadi Anda. Informasi Anda tidak akan dijual, diperdagangkan, atau dialihkan kepada pihak ketiga tanpa persetujuan Anda, kecuali diperlukan untuk keperluan pemrosesan pembayaran.</p>
        </div>

        <div class="card">
            <h2>Penyimpanan Data</h2>
            <p>Data pribadi Anda disimpan selama diperlukan untuk memberikan layanan dan memenuhi kewajiban hukum yang berlaku. Anda dapat meminta penghapusan data dengan menghubungi kami melalui WhatsApp.</p>
        </div>

        <div class="card">
            <h2>Hubungi Kami</h2>
            <p>Jika Anda memiliki pertanyaan mengenai kebijakan privasi ini, silakan hubungi kami di:</p>
            <ul>
                <li>WhatsApp: +62 812-3456-7890</li>
                <li>Email: bintangsportcenter@gmail.com</li>
                <li>Alamat: Gresik, Jawa Timur</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <div class="footer-inner">
            <div class="brand-logo">
                <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport" />
                <p class="tagline">Lapangan olahraga dengan fasilitas dan kenyamanan yang berkualitas untuk mendukung gaya hidup sehat Anda.</p>
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
                    <li><a href="{{ route('kebijakan') }}" style="color:white;">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('syarat') }}">Syarat & Ketentuan</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 Bintang Sport Center. All rights reserved.
        </div>
    </div>

</body>
</html>