<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Cek Riwayat Pesanan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fafb;
            color: #1f2937;
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
            flex: 1;
        }
        .card {
            background: white;
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }
        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }
        .input-wrapper {
            display: flex;
            align-items: center;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            overflow: hidden;
        }
        .input-wrapper span {
            background: #f3f4f6;
            padding: 12px 16px;
            color: #4b5563;
            font-weight: 500;
            border-right: 1px solid #d1d5db;
        }
        .input-wrapper input {
            border: none;
            padding: 12px 16px;
            flex: 1;
            font-size: 16px;
            outline: none;
            min-width: 0;
        }
        .input-wrapper input:focus {
            outline: 2px solid #3b82f6;
            outline-offset: -1px;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: #2563eb;
        }
        .secure-text {
            margin-top: 16px;
            font-size: 14px;
            color: #6b7280;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .secure-text::before {
            content: "🔒";
            font-size: 16px;
        }

        /* Menu Cepat */
        .menu-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 40px;
        }
        .menu-section h3 {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        .menu-section ul {
            list-style: none;
            padding: 0;
        }
        .menu-section ul li {
            margin-bottom: 8px;
        }
        .menu-section ul li a {
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        .menu-section ul li a:hover {
            color: #3b82f6;
        }

        /* Footer */
        .footer {
            background: #111827;
            color: #9ca3af;
            padding: 30px 20px;
            text-align: center;
            margin-top: 40px;
        }
        .footer .brand {
            font-size: 18px;
            font-weight: 700;
            color: white;
            margin-bottom: 4px;
        }
        .footer .tagline {
            font-size: 14px;
            color: #9ca3af;
            max-width: 500px;
            margin: 8px auto 0;
        }

        @media (max-width: 600px) {
            .card { padding: 24px 16px; }
            .menu-section { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Cek Riwayat Pesanan</h1>
            <p class="subtitle">Masukkan nomor WhatsApp Anda untuk melihat riwayat pemesanan lapangan.</p>

            <form action="{{ route('booking.cekStatus') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nomor_hp">Nomor WhatsApp</label>
                    <div class="input-wrapper">
                        <span>+62</span>
                        <input type="text" id="nomor_hp" name="nomor_hp" placeholder="813 7429 280" value="{{ old('nomor_hp') }}" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Cek Riwayat</button>
            </form>

            <div class="secure-text">Data Anda terlindungi oleh enkripsi keamanan kami.</div>

            <!-- Menu Cepat -->
            <div class="menu-section">
                <div>
                    <h3>Menu Cepat</h3>
                    <ul>
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li><a href="{{ route('lapangan.public') ?? '#' }}">Lapangan</a></li>
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
        </div>
    </div>

    <div class="footer">
        <div class="brand">Bintang Sport Center</div>
        <div class="tagline">where everyone comes to play and sport.<br>Lapangan olahraga dengan fasilitas dan kenyamanan yang berkualitas untuk mendukung gaya hidup sehat Anda.</div>
    </div>
</body>
</html>