<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Pembayaran - Bintang Sport</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        }

        /* ===== Navbar atas (baru) ===== */
        .navbar {
            width: 100%;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        }
        .navbar .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #0052cc;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
        }
        .navbar .back-link:hover {
            text-decoration: underline;
        }
        .navbar .navbar-title {
            font-size: 13px;
            font-weight: 700;
            color: #03045e;
            margin-left: 4px;
            border-left: 1px solid #e2e8f0;
            padding-left: 10px;
        }

        .page-body {
            width: 100%;
            display: flex;
            justify-content: center;
            padding: 30px 20px;
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>

<!-- ===== Navbar atas berisi tombol kembali (baru) ===== -->
<div class="navbar">
    <a href="{{ url('/booking') }}" class="back-link">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="page-body">
    <div class="card-wrap">
        <span class="badge-tab">PEMBAYARAN</span>
        <div class="container">

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
</div>

<script>
    // Countdown timer 10 menit
    let timeLeft = 60; // 10 menit
    let isExpired = false;
    const countdownEl = document.getElementById('countdown');

    function updateCountdown() {
        if (timeLeft > 0) {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;

        countdownEl.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
           
        timeLeft--;
        } else if (!isExpired){
            isExpired = true;

            clearInterval(timerInterval);

            countdownEl.innerHTML = '<i class="bi bi-alarm"></i> Habis';

            document.querySelector('.btn-success').disabled = true;
            document.querySelector('.btn-success').style.background = '#94a3b8';
            document.querySelector('.btn-success').style.cursor = 'not-allowed';

            setTimeout(() => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Waktu Pembayaran Habis',
                    text: 'Anda akan diarahkan ke Riwayat Booking.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.style.fontSize = '14px';
                        toast.style.width = 'auto';
                        toast.style.padding = '12px 20px';
                    }
                    }).then(() => {
                        window.location.href = '{{ route("booking.cek") }}';
                    });

                }, 500);
        }
    }
                
    updateCountdown();
    const timerInterval = setInterval(updateCountdown, 1000);
</script>

</body>
</html>