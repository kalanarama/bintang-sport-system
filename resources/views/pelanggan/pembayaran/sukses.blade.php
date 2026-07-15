<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking Berhasil - Bintang Sport</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f4f7fa;
            color: #1f2937;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card-wrap {
            max-width: 340px;
            width: 100%;
        }

        .badge-tab {
            display: inline-block;
            background: #0052cc;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 6px 14px;
            border-radius: 8px 8px 0 0;
            margin-left: 4px;
        }

        .container {
            background: white;
            border-radius: 14px;
            border-top-left-radius: 0;
            padding: 20px 18px 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
            text-align: center;
        }

        .icon-success {
            width: 44px;
            height: 44px;
            background: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
        }
        .icon-success svg {
            width: 22px;
            height: 22px;
        }

        h2 {
            font-size: 16px;
            font-weight: 700;
            color: #03045e;
            margin-bottom: 2px;
        }
        .subtitle {
            color: #94a3b8;
            font-size: 11px;
            margin-bottom: 14px;
        }

        .kode-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 14px;
        }
        .kode-label {
            font-size: 10px;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .kode-value {
            font-size: 14px;
            font-weight: 700;
            color: #03045e;
            letter-spacing: 0.3px;
        }

        .info-block {
            text-align: left;
            margin-bottom: 12px;
        }
        .lapangan-name {
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .lapangan-detail {
            font-size: 12px;
            color: #475569;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .total-block {
            text-align: left;
            margin-bottom: 12px;
        }
        .total-label {
            font-size: 12px;
            color: #475569;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .total-bayar {
            font-size: 16px;
            font-weight: 700;
            color: #16a34a;
        }

        .note {
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 14px;
        }

        .btn-primary {
            display: inline-block;
            background: #0052cc;
            color: white;
            border: none;
            padding: 11px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
            width: 100%;
        }
        .btn-primary:hover {
            background: #003d99;
        }

        @media (max-width: 480px) {
            .card-wrap { max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="card-wrap">
        <span class="badge-tab">BOOKING BERHASIL</span>
        <div class="container">
            <div class="icon-success">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 13l4 4L19 7" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h2>Booking Berhasil!</h2>
            <p class="subtitle">Pembayaran telah diterima.</p>

            <div class="kode-box">
                <div class="kode-label">Kode Booking</div>
                <div class="kode-value">{{ $booking->kode_booking }}</div>
            </div>

            <div class="info-block">
                <div class="lapangan-name">{{ $booking->jadwal->lapangan->nama_lapangan ?? 'Lapangan' }}</div>
                <div class="lapangan-detail">
                    📅 {{ \Carbon\Carbon::parse($booking->jadwal->tanggal_jadwal)->format('d F Y') }}
                </div>
                <div class="lapangan-detail">
                    🕐 {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H.i') }}
                </div>
            </div>

            <div class="total-block">
                <div class="total-label">Total Pembayaran</div>
                <div class="total-bayar">Rp{{ number_format($booking->total_bayar, 0, ',', '.') }}</div>
            </div>

            <p class="note">📲 Detail booking telah dikirim ke WhatsApp Anda.</p>

            <a href="{{ url('/') }}" class="btn-primary">Kembali ke Beranda</a>
        </div>
    </div>

</body>
</html>