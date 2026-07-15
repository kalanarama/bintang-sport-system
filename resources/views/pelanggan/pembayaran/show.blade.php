<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pembayaran - Bintang Sport</title>
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
            flex-direction: column;
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
            padding: 18px 18px 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 10px;
            color: #0052cc;
            font-weight: 600;
            font-size: 12px;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        h2 {
            font-size: 16px;
            font-weight: 700;
            color: #03045e;
            margin-bottom: 10px;
        }

        .qris-wrapper {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            text-align: center;
            margin-bottom: 12px;
        }
        .qris-wrapper img {
            display: block;
            margin: 0 auto;
            width: 130px;
            height: 130px;
        }
        .qris-wrapper .caption {
            font-size: 11px;
            color: #64748b;
            margin-top: 8px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .detail-row:last-of-type {
            border-bottom: none;
        }
        .detail-label {
            color: #475569;
            font-size: 13px;
            font-weight: 500;
        }
        .detail-value {
            font-weight: 700;
            font-size: 13px;
            color: #0f172a;
        }
        .total {
            font-size: 15px;
            color: #16a34a;
        }
        .countdown-value {
            font-size: 14px;
            color: #dc2626;
        }

        .status-line {
            margin-top: 12px;
            text-align: center;
        }
        .status-line .status-title {
            font-size: 12px;
            color: #0052cc;
            font-weight: 600;
        }
        .status-line small {
            display: block;
            font-weight: 400;
            color: #94a3b8;
            font-size: 11px;
            margin-top: 3px;
            line-height: 1.4;
        }

        .btn-success {
            display: block;
            margin-top: 16px;
            width: 100%;
            background: #16a34a;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            text-align: center;
            transition: background 0.2s;
        }
        .btn-success:hover {
            background: #15803d;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            margin-top: 10px;
            text-align: center;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 480px) {
            .card-wrap { max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="card-wrap">
    <span class="badge-tab">PEMBAYARAN</span>
    <div class="container">
        <a href="{{ url('/') }}" class="back-link">← Kembali</a>

        <h2>Pembayaran</h2>

        <!-- QRIS Statis -->
        <div class="qris-wrapper">
            <img src="{{ asset('img/QRIS.jpeg') }}" alt="QRIS Bank">
            <p class="caption">Scan QRIS di atas untuk melakukan pembayaran</p>
        </div>

        <!-- Detail -->
        <div class="detail-row">
            <span class="detail-label">Total Pembayaran</span>
            <span class="detail-value total">Rp{{ number_format($booking->total_bayar, 0, ',', '.') }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Sisa Waktu Pembayaran</span>
            <span class="detail-value countdown-value" id="countdown">09:59</span>
        </div>

        <!-- Status -->
        <div class="status-line">
            <p class="status-title">Menunggu pembayaran...</p>
            <small>Setelah pembayaran berhasil, status booking akan otomatis terupdate.</small>
        </div>

        <!-- Tombol Konfirmasi (tanpa popup) -->
        <form action="{{ route('pembayaran.konfirmasi', $booking->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-success">Saya Sudah Membayar</button>
        </form>

        <!-- Flash Messages -->
        @if(session('info'))
            <div class="alert-info">{{ session('info') }}</div>
        @endif
        @if(session('success'))
            <div class="alert-info alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-info alert-error">{{ session('error') }}</div>
        @endif
    </div>
</div>

<script>
    // Countdown timer 10 menit
    let timeLeft = 600; // 10 menit
    const countdownEl = document.getElementById('countdown');

    function updateCountdown() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        if (timeLeft > 0) {
            timeLeft--;
        } else {
            countdownEl.textContent = '⏰ Habis';
            clearInterval(timerInterval);
        }
    }
    updateCountdown();
    const timerInterval = setInterval(updateCountdown, 1000);
</script>

</body>
</html>