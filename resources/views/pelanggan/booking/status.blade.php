<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Riwayat Pesanan - Bintang Sport Center</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
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

        .topbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            padding: 0 60px;
            width: 100%;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
        }
        .topbar-left img {
            height: 38px;
            width: auto;
            display: block;
        }
        .topbar-title {
            font-size: 16px;
            font-weight: 700;
            color: #10275b;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        .topbar-right {
            display: flex;
            justify-content: flex-end;
            width: 120px;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            background: #f1f5f9;
            border: none;
            border-radius: 8px;
            padding: 7px 14px;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
        }
        .btn-back:hover { background: #e2e8f0; color: #10275b; }



        .container {
            width: 100%;
            margin: 84px auto 60px;
            padding: 0 60px;
            flex: 1;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 32px;
        }
        .info-card {
            background: white;
            border-radius: 14px;
            padding: 24px 28px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .info-card .label { font-size: 14px; color: #64748b; margin-bottom: 6px; }
        .info-card .name { font-size: 22px; font-weight: 700; color: #10275b; }
        .info-card .count-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0f4ff;
            border-radius: 30px;
            padding: 8px 18px;
            font-size: 16px;
            font-weight: 700;
            color: #10275b;
            margin-top: 8px;
            align-self: flex-start;
        }

        .filter-wrap {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .filter-btns { display: flex; gap: 8px; flex-wrap: wrap; }
        .filter-btn {
            padding: 8px 18px;
            border-radius: 30px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: 0.2s;
        }
        .filter-btn.active { background: #0756d9; border-color: #0756d9; color: white; }
        .filter-btn:hover:not(.active) { border-color: #0756d9; color: #0756d9; }

        .booking-card {
            background: white;
            border-radius: 16px;
            padding: 24px 28px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 16px;
            transition: 0.2s;
        }
        .booking-card.dibatalkan { opacity: 0.7; }
        .booking-card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }
        .booking-kode { font-size: 13px; font-weight: 700; color: #0756d9; margin-bottom: 4px; }
        .booking-kode.dibatalkan { color: #94a3b8; }
        .booking-nama {
            font-size: 18px;
            font-weight: 700;
            color: #10275b;
            margin-bottom: 16px;
        }
        .booking-nama.dibatalkan { text-decoration: line-through; color: #94a3b8; }
        .booking-total-label { font-size: 12px; color: #64748b; text-align: right; }
        .booking-total-value { font-size: 18px; font-weight: 700; color: #10275b; text-align: right; }
        .booking-total-value.dibatalkan { color: #94a3b8; }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }
        .badge-tertunda { background: #fef3c7; color: #d97706; }
        .badge-berhasil { background: #dcfce7; color: #16a34a; }
        .badge-dibatalkan { background: #fce4ec; color: #c62828; }

        .booking-meta {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            padding: 16px 0;
            border-top: 1px solid #f0f4ff;
            border-bottom: 1px solid #f0f4ff;
            margin-bottom: 16px;
        }
        .meta-item .meta-label { font-size: 12px; color: #64748b; margin-bottom: 4px; display: flex; align-items: center; gap: 6px; }
        .meta-item .meta-value { font-size: 13px; font-weight: 600; color: #10275b; }
        .meta-item .meta-value.status-tertunda { color: #d97706; }
        .meta-item .meta-value.status-berhasil { color: #16a34a; }
        .meta-item .meta-value.status-dibatalkan { color: #c62828; }

        .booking-actions { display: flex; gap: 10px; }
        .btn-bayar {
            background: #0756d9; color: white; border: none;
            padding: 10px 20px; border-radius: 8px; font-size: 13px;
            font-weight: 600; cursor: pointer; display: inline-flex;
            align-items: center; gap: 8px; transition: 0.2s;
            text-decoration: none;
        }
        .btn-bayar:hover { background: #0348b9; }
        .btn-outline {
            background: white; color: #10275b;
            border: 1.5px solid #e2e8f0;
            padding: 10px 20px; border-radius: 8px; font-size: 13px;
            font-weight: 600; cursor: pointer; display: inline-flex;
            align-items: center; gap: 8px; transition: 0.2s;
            text-decoration: none;
        }
        .btn-outline:hover { border-color: #0756d9; color: #0756d9; }
        .btn-disabled {
            background: #e2e8f0; color: #94a3b8;
            border: none; padding: 10px 20px; border-radius: 8px;
            font-size: 13px; font-weight: 600; cursor: not-allowed;
            display: inline-flex; align-items: center; gap: 8px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }
        .empty-state i { font-size: 48px; margin-bottom: 16px; display: block; }

        @media (max-width: 700px) {
            .topbar { padding: 0 20px; }
            .topbar-title { font-size: 14px; }
            .booking-meta { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-left">
        <a href="{{ url('/beranda') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport" />
        </a>
    </div>
    <div class="topbar-title">Riwayat Pesanan</div>
    <div class="topbar-right">
        <a href="{{ route('booking.cek') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="container">

    @php $pelanggan = $bookings->first()->pelanggan; @endphp

    <div class="info-grid">
        <div class="info-card">
            <div class="label">Selamat datang kembali,</div>
            <div class="name">{{ $pelanggan->nama_pelanggan }}</div>
        </div>
        <div class="info-card">
            <div class="label">Anda telah membooking sebanyak</div>
            <div class="count-badge">
                <i class="fas fa-futbol" style="color:#0756d9;"></i> {{ $bookings->count() }} Booking
            </div>
        </div>
    </div>

    <div class="filter-wrap">
        <div class="filter-btns">
            <button class="filter-btn active" data-filter="semua">Semua Status</button>
            <button class="filter-btn" data-filter="Tertunda">Tertunda</button>
            <button class="filter-btn" data-filter="Berhasil">Berhasil</button>
            <button class="filter-btn" data-filter="Dibatalkan">Dibatalkan</button>
        </div>
    </div>

    <div id="bookingList">
        @forelse($bookings as $booking)
        @php
            $status = $booking->status;
            $isDibatalkan = $status === 'Dibatalkan';
        @endphp
        <div class="booking-card {{ $isDibatalkan ? 'dibatalkan' : '' }}" data-status="{{ $status }}">
            <div class="booking-card-top">
                <div>
                    <div class="booking-kode {{ $isDibatalkan ? 'dibatalkan' : '' }}">
                        #{{ $booking->kode_booking }}
                        <span class="badge badge-{{ strtolower($status) }}">{{ $status }}</span>
                    </div>
                    <div class="booking-nama {{ $isDibatalkan ? 'dibatalkan' : '' }}">
                        {{ $booking->jadwal->lapangan->nama_lapangan ?? '-' }}
                    </div>
                </div>
                <div>
                    <div class="booking-total-label">Total Pembayaran</div>
                    <div class="booking-total-value {{ $isDibatalkan ? 'dibatalkan' : '' }}">
                        Rp{{ number_format($booking->total_bayar, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <div class="booking-meta">
                <div class="meta-item">
                    <div class="meta-label"><i class="fas fa-calendar-alt"></i> Tanggal</div>
                    <div class="meta-value">
                        {{ \Carbon\Carbon::parse($booking->jadwal->tanggal_jadwal)->format('d M Y') }}
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-label"><i class="fas fa-clock"></i> Waktu</div>
                    <div class="meta-value">
                        {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                    </div>
                </div>
                <div class="meta-item">
                    <div class="meta-label"><i class="fas fa-futbol"></i> Lapangan</div>
                    <div class="meta-value">{{ $booking->jadwal->lapangan->nama_lapangan ?? '-' }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label"><i class="fas fa-circle-half-stroke"></i> Status</div>
                    <div class="meta-value status-{{ strtolower($status) }}">{{ $status }}</div>
                </div>
            </div>

            <div class="booking-actions">
                @if($status === 'Tertunda')
                    <a href="{{ route('pembayaran.show', $booking->id) }}" class="btn-bayar">
                        Bayar Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                    <button class="btn-outline">Batalkan Pesanan</button>
                @elseif($status === 'Berhasil')
                    <a href="{{ route('jadwal.public') }}" class="btn-bayar">
                        <i class="fas fa-rotate-left"></i> Pesan Lagi
                    </a>
                @else
                    <button class="btn-disabled" disabled>
                        <i class="fas fa-ban"></i> Batal
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-calendar-xmark"></i>
            Tidak ada riwayat booking.
        </div>
        @endforelse
    </div>

</div>

<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            document.querySelectorAll('.booking-card').forEach(card => {
                if (filter === 'semua' || card.dataset.status === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>

</body>
</html>