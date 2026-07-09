<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Riwayat - Bintang Sport</title>
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
            max-width: 460px;
            margin: 105px auto 20px;
            padding: 0 20px;
            flex: 1;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 36px 28px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        .icon-wrapper { text-align: center; margin-bottom: 16px; }
        .icon-wrapper .icon-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px; height: 56px;
            background: #0052cc;
            border-radius: 14px;
        }
        .icon-wrapper .icon-box img { width: 40px; height: 40px; object-fit: contain; }

        h1 { font-size: 25px; font-weight: 700; color: #03045e; margin-bottom: 6px; text-align: center; }
        .subtitle { color: #475569; font-size: 15px; margin-bottom: 24px; text-align: center; }

        .form-group { margin-bottom: 18px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; color: #1e293b; }

        .input-field {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 16px;
            outline: none;
            transition: border 0.2s, box-shadow 0.2s;
            background: white;
        }
        .input-field:focus {
            border-color: #0052cc;
            box-shadow: 0 0 0 3px rgba(0,82,204,0.15);
        }
        .input-field.is-invalid {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220,38,38,0.15);
        }

        .error-msg {
            color: #dc2626;
            font-size: 13px;
            display: block;
            margin-top: 6px;
            line-height: 1.4;
        }
        .hint-msg {
            color: #94a3b8;
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        .btn-primary {
            background: #0052cc;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-primary:hover { background: #003d99; }
        .btn-primary svg { width: 18px; height: 18px; stroke: white; fill: none; stroke-width: 2.4; }

        .secure-text {
            margin-top: 16px;
            font-size: 14px;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
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
            <a href="{{ route('booking.cek') }}" class="active">Riwayat</a>
        </nav>
        <a href="{{ route('jadwal.public') }}" class="btn-nav">Booking Sekarang</a>
    </div>

    <div class="container">
        <div class="card">

            <div class="icon-wrapper">
                <div class="icon-box">
                    <img src="{{ asset('img/notebook_fill@3x.png') }}" alt="Riwayat Pesanan" />
                </div>
            </div>

            <h1>Cek Riwayat Pesanan</h1>
            <p class="subtitle">Masukkan nomor WhatsApp Anda untuk melihat riwayat pemesanan lapangan.</p>

            <form action="{{ route('booking.cekStatus') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nomor_hp">Nomor WhatsApp</label>
                    <input
                        type="text"
                        id="nomor_hp"
                        name="nomor_hp"
                        class="input-field {{ $errors->has('nomor_hp') ? 'is-invalid' : '' }}"
                        placeholder="Contoh: 0812 3456 7890"
                        value="{{ old('nomor_hp') }}"
                        inputmode="numeric"
                        autocomplete="off"
                        maxlength="15"
                        oninput="formatNomor(this)"
                    >

                    @error('nomor_hp')
                        <small class="error-msg">{{ $message }}</small>
                    @enderror

                    @if (!$errors->has('nomor_hp'))
                        <small class="hint-msg">Masukkan nomor yang digunakan saat booking</small>
                    @endif
                </div>

                <button type="submit" class="btn-primary">
                    Cek Riwayat
                    <svg viewBox="0 0 24 24">
                        <line x1="5" y1="12" x2="19" y2="12" />
                        <polyline points="12 5 19 12 12 19" />
                    </svg>
                </button>
            </form>

            <div class="secure-text">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="#64748b" stroke-width="2">
                    <path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z" />
                    <path d="M9 12l2 2 4-4" />
                </svg>
                Data Anda terlindungi oleh enkripsi keamanan kami.
            </div>

        </div>
    </div>

    <div class="footer">
        <div class="footer-inner">
            <div class="brand-logo">
                <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport" />
                <p class="tagline">
                    Lapangan olahraga dengan fasilitas dan kenyamanan yang berkualitas untuk mendukung gaya hidup sehat Anda.
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
            &copy; {{ date('Y') }} Bintang Sport Center. All rights reserved.
        </div>
    </div>

    <script>
        function formatNomor(input) {
            let cursorPos = input.selectionStart;
            let prevLen = input.value.length;

            let value = input.value.replace(/\D/g, '');
            let formatted = '';
            if (value.length > 0) formatted += value.substring(0, 4);
            if (value.length > 4) formatted += ' ' + value.substring(4, 8);
            if (value.length > 8) formatted += ' ' + value.substring(8, 12);
            if (value.length > 12) formatted += ' ' + value.substring(12, 15);

            input.value = formatted;

            let diff = formatted.length - prevLen;
            input.setSelectionRange(cursorPos + diff, cursorPos + diff);
        }
    </script>

</body>
</html>