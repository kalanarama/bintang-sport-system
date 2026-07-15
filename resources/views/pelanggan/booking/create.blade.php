<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Booking Lapangan - Bintang Sport</title>
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

        .page-wrapper {
            max-width: 1100px;
            margin: 100px auto 60px;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 28px;
            align-items: start;
            flex: 1;
        }

        .left-title { font-size: 26px; font-weight: 700; color: #03045e; margin-bottom: 4px; }
        .left-sub { color: #64748b; font-size: 15px; margin-bottom: 20px; }

        .lapangan-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .lapangan-tab {
            padding: 8px 18px;
            border-radius: 20px;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: 0.2s;
        }
        .lapangan-tab:hover { border-color: #0052cc; color: #0052cc; }
        .lapangan-tab.active {
            background: #0052cc;
            border-color: #0052cc;
            color: white;
        }

        .month-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .month-select-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 14px;
            font-weight: 600;
            font-size: 14px;
            color: #10275b;
        }
        .month-select-wrap i { color: #0052cc; }
        .month-select-wrap select {
            border: none;
            outline: none;
            font-weight: 600;
            font-size: 14px;
            color: #10275b;
            background: transparent;
            cursor: pointer;
        }
        .nav-arrows { display: flex; gap: 8px; }
        .nav-arrows button {
            width: 34px; height: 34px;
            border-radius: 50%;
            border: 1.5px solid #e2e8f0;
            background: white;
            font-size: 14px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #475569;
            transition: 0.2s;
        }
        .nav-arrows button:hover { background: #f1f5f9; }

        .week-strip {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 2px;
        }
        .day-btn {
            flex: 0 0 auto;
            min-width: 62px;
            padding: 10px 8px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: white;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
        }
        .day-btn .day-name { font-size: 10px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .day-btn .day-num { font-size: 20px; font-weight: 700; color: #10275b; margin-top: 2px; }
        .day-btn:hover { border-color: #0052cc; }
        .day-btn.active { background: #0052cc; border-color: #0052cc; }
        .day-btn.active .day-name, .day-btn.active .day-num { color: white; }

        .slot-panel {
            background: white;
            border-radius: 14px;
            border: 1.5px solid #e2e8f0;
            padding: 20px;
        }
        .slot-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .slot-header h3 { font-size: 15px; font-weight: 700; color: #03045e; }
        .legend { display: flex; gap: 14px; align-items: center; font-size: 12px; color: #64748b; flex-wrap: wrap; }
        .legend span { display: flex; align-items: center; gap: 5px; }
        .dot { width: 11px; height: 11px; border-radius: 50%; display: inline-block; }
        .dot-tersedia { border: 1.5px solid #cbd5e1; background: white; }
        .dot-penuh { background: #cbd5e1; }
        .dot-dipilih { background: #0052cc; }

        .slot-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .slot-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 10px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
            background: white;
            position: relative;
        }
        .slot-card:hover:not(.penuh) { border-color: #0052cc; }
        .slot-card.penuh { background: #f1f5f9; cursor: not-allowed; border-color: #e2e8f0; }
        .slot-card.dipilih { background: #0052cc; border-color: #0052cc; }
        .slot-card .promo-label {
            font-size: 10px; font-weight: 700; color: #0052cc;
            text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px;
        }
        .slot-card.dipilih .promo-label { color: #93c5fd; }
        .slot-card .jam { font-size: 13px; font-weight: 700; color: #10275b; }
        .slot-card.penuh .jam { color: #94a3b8; }
        .slot-card.dipilih .jam { color: white; }
        .slot-card .harga-coret { font-size: 11px; color: #94a3b8; text-decoration: line-through; margin-top: 2px; }
        .slot-card.dipilih .harga-coret { color: #93c5fd; }
        .slot-card .harga { font-size: 12px; font-weight: 600; color: #475569; margin-top: 1px; }
        .slot-card.penuh .harga { color: #cbd5e1; }
        .slot-card.dipilih .harga { color: #e0f2fe; }

        .no-slot { text-align: center; padding: 40px 20px; color: #94a3b8; font-size: 14px; }

        .summary-card {
            background: white;
            border-radius: 16px;
            border: 1.5px solid #e2e8f0;
            padding: 24px;
            position: sticky;
            top: 90px;
        }
        .summary-card h2 { font-size: 17px; font-weight: 700; color: #03045e; margin-bottom: 20px; }

        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px; }
        .form-group input {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 14px;
            outline: none;
            transition: border 0.2s, box-shadow 0.2s;
            color: #10275b;
        }
        .form-group input:focus { border-color: #0052cc; box-shadow: 0 0 0 3px rgba(0,82,204,0.12); }
        .form-group input.is-invalid { border-color: #dc2626; }
        .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }

        .phone-wrap {
            display: flex;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
            transition: border 0.2s, box-shadow 0.2s;
        }
        .phone-wrap:focus-within { border-color: #0052cc; box-shadow: 0 0 0 3px rgba(0,82,204,0.12); }
        .phone-prefix {
            background: #f8fafc; padding: 11px 12px; font-size: 14px;
            font-weight: 600; color: #475569; border-right: 1.5px solid #e2e8f0; white-space: nowrap;
        }
        .phone-wrap input {
            border: none; border-radius: 0; flex: 1;
            padding: 11px 12px; font-size: 14px; outline: none; color: #10275b;
        }
        .phone-wrap.is-invalid { border-color: #dc2626; }

        .selected-detail {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 16px;
            display: none;
        }
        .selected-detail.show { display: block; }
        .selected-detail .det-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px; }
        .selected-detail .det-nama { font-size: 14px; font-weight: 700; color: #03045e; display: flex; align-items: center; gap: 8px; }
        .selected-detail .det-nama i { color: #0052cc; }
        .selected-detail .det-harga { font-size: 14px; font-weight: 700; color: #0052cc; }
        .selected-detail .det-info { font-size: 12px; color: #64748b; margin-top: 2px; }

        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 16px 0; }

        .price-row { display: flex; justify-content: space-between; font-size: 13px; color: #64748b; margin-bottom: 8px; }
        .price-row.diskon { color: #16a34a; }
        .price-row.total { font-size: 15px; font-weight: 700; color: #03045e; margin-top: 4px; }
        .price-row.total .total-val { color: #0052cc; font-size: 16px; }

        .price-placeholder { text-align: center; padding: 16px 0; color: #94a3b8; font-size: 13px; }

        .btn-submit {
            width: 100%; background: #0052cc; color: white; border: none;
            padding: 14px; border-radius: 10px; font-size: 15px; font-weight: 700;
            cursor: pointer; transition: background 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 16px;
        }
        .btn-submit:hover { background: #003d99; }
        .btn-submit:disabled { background: #cbd5e1; cursor: not-allowed; }

        .footer {
            background: #03045e; color: #f4f7fa;
            padding: 50px 40px 30px; margin-top: 60px;
        }
        .footer-inner {
            max-width: 1100px; margin: 0 auto;
            display: grid; grid-template-columns: 1.6fr 1fr 1fr; gap: 30px;
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
            margin-top: 40px; padding-top: 20px;
            text-align: center; font-size: 14px; color: #cbd5e1;
        }

        @media (max-width: 900px) {
            .page-wrapper { grid-template-columns: 1fr; }
            .summary-card { position: static; }
        }
        @media (max-width: 700px) {
            .navbar { padding: 14px 20px; }
            .navbar nav { display: none; }
            .slot-grid { grid-template-columns: repeat(2, 1fr); }
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
        <a href="{{ route('jadwal.public') }}" class="active">Jadwal</a>
        <a href="{{ route('booking.cek') }}">Riwayat</a>
    </nav>
    <a href="{{ route('booking.create') }}" class="btn-nav">Booking Sekarang</a>
</div>

<form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
@csrf
<input type="hidden" name="jadwal_id" id="selected_jadwal_id">

<div class="page-wrapper">

    {{-- KIRI --}}
    <div>
        <h1 class="left-title" id="pageTitle">Jadwal Lapangan</h1>
        <p class="left-sub">Pilih waktu yang tersedia untuk mulai berolahraga hari ini.</p>

        @php
            $lapangans = $jadwals->pluck('lapangan')->unique('id')->values();
        @endphp
        <div class="lapangan-tabs">
            @foreach($lapangans as $i => $lap)
                <button type="button"
                    class="lapangan-tab {{ $i === 0 ? 'active' : '' }}"
                    data-lapangan-id="{{ $lap->id }}"
                    onclick="pilihLapangan({{ $lap->id }}, '{{ $lap->nama_lapangan }}', this)">
                    {{ $lap->nama_lapangan }}
                </button>
            @endforeach
        </div>

        <div class="month-nav">
            <div class="month-select-wrap">
                <i class="fa-regular fa-calendar"></i>
                <select id="monthSelect" onchange="renderWeek()">
                    @php
                        $bulanList = [
                            1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                            5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                            9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
                        ];
                        $now = now();
                    @endphp
                    @foreach($bulanList as $num => $nama)
                        <option value="{{ $num }}" {{ $now->month == $num ? 'selected' : '' }}>
                            {{ $nama }} {{ $now->year }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="nav-arrows">
                <button type="button" onclick="geserMinggu(-1)">&#8249;</button>
                <button type="button" onclick="geserMinggu(1)">&#8250;</button>
            </div>
        </div>

        <div class="week-strip" id="weekStrip"></div>

        <div class="slot-panel">
            <div class="slot-header">
                <h3>Ketersediaan Lapangan</h3>
                <div class="legend">
                    <span><span class="dot dot-tersedia"></span> Tersedia</span>
                    <span><span class="dot dot-penuh"></span> Penuh</span>
                    <span><span class="dot dot-dipilih"></span> Dipilih</span>
                </div>
            </div>
            <div class="slot-grid" id="slotGrid">
                <div class="no-slot">Pilih lapangan dan tanggal untuk melihat slot.</div>
            </div>
        </div>
    </div>

    {{-- KANAN --}}
    <div>
        <div class="summary-card">
            <h2>Ringkasan Pesanan</h2>

            <div class="form-group">
                <label for="nama_pelanggan">Nama Lengkap</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan"
                    placeholder="Contoh: Awan Santosa"
                    value="{{ old('nama_pelanggan') }}"
                    class="{{ $errors->has('nama_pelanggan') ? 'is-invalid' : '' }}">
                @error('nama_pelanggan')
                    <small class="error-msg">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="nomor_hp">Nomor WhatsApp</label>
                <input type="text" id="nomor_hp" name="nomor_hp"
                    placeholder="Contoh: 0812 3456 7890"
                    value="{{ old('nomor_hp') }}"
                    inputmode="numeric"
                    autocomplete="off"
                    maxlength="15"
                    class="{{ $errors->has('nomor_hp') ? 'is-invalid' : '' }}"
                    oninput="formatHP(this)">
                @error('nomor_hp')
                    <small class="error-msg">{{ $message }}</small>
                @enderror
            </div>

            <div class="selected-detail" id="selectedDetail">
                <div class="det-top">
                    <span class="det-nama">
                        <i class="fa-regular fa-futbol"></i>
                        <span id="detNama">-</span>
                    </span>
                    <span class="det-harga" id="detHargaAsli">-</span>
                </div>
                <div class="det-info" id="detInfo">-</div>
            </div>

            <div id="priceSummary">
                <div class="price-placeholder" id="pricePlaceholder">
                    Pilih slot jadwal untuk melihat total harga.
                </div>
                <div id="priceBreakdown" style="display:none">
                    <hr class="divider">
                    <div class="price-row">
                        <span>Subtotal</span>
                        <span id="subtotalVal">-</span>
                    </div>
                    <div class="price-row diskon" id="diskonRow" style="display:none">
                        <span>Voucher Diskon</span>
                        <span id="diskonVal">-</span>
                    </div>
                    <hr class="divider">
                    <div class="price-row total">
                        <span>Total</span>
                        <span class="total-val" id="totalVal">-</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit" disabled>
                Lanjutkan Pembayaran
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

</div>
</form>

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
const allJadwals = @json($jadwalData);
console.log(allJadwals);

@verbatim
let activeLapanganId = null;
let activeLapanganNama = '';
let activeDate = null;
let selectedJadwal = null;
let weekOffset = 0;

window.addEventListener('DOMContentLoaded', function() {
    const firstTab = document.querySelector('.lapangan-tab');
    if (firstTab) {
        const id = parseInt(firstTab.dataset.lapanganId);
        const nama = firstTab.textContent.trim();
        pilihLapangan(id, nama, firstTab);
    }
    renderWeek();
});

function pilihLapangan(id, nama, el) {
    activeLapanganId = id;
    activeLapanganNama = nama;
    selectedJadwal = null;
    document.querySelectorAll('.lapangan-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('pageTitle').textContent = 'Jadwal Lapangan ' + nama;
    renderWeek();
    resetSummary();
}

function renderWeek() {
    const monthVal = parseInt(document.getElementById('monthSelect').value);
    const year = new Date().getFullYear();
    const startOfMonth = new Date(year, monthVal - 1, 1);
    const weekStart = new Date(startOfMonth);
    weekStart.setDate(startOfMonth.getDate() + weekOffset * 7);
    const strip = document.getElementById('weekStrip');
    strip.innerHTML = '';
    const hariNama = ['MINGGU','SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'];
    for (let i = 0; i < 7; i++) {
        const d = new Date(weekStart);
        d.setDate(weekStart.getDate() + i);
        if (d.getMonth() !== monthVal - 1) continue;
        const dateStr = d.toISOString().split('T')[0];
        const btn = document.createElement('div');
        btn.className = 'day-btn' + (activeDate === dateStr ? ' active' : '');
        btn.innerHTML = `<div class="day-name">${hariNama[d.getDay()]}</div><div class="day-num">${String(d.getDate()).padStart(2,'0')}</div>`;
        btn.onclick = () => pilihTanggal(dateStr, btn);
        strip.appendChild(btn);
    }
    if (activeDate) renderSlots();
}

function geserMinggu(arah) {
    weekOffset += arah;
    renderWeek();
}

function pilihTanggal(dateStr, el) {
    activeDate = dateStr;
    selectedJadwal = null;
    document.querySelectorAll('.day-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
    renderSlots();
    resetSummary();
}

function renderSlots() {
    const grid = document.getElementById('slotGrid');
    grid.innerHTML = '';
    if (!activeLapanganId || !activeDate) {
        grid.innerHTML = '<div class="no-slot">Pilih lapangan dan tanggal untuk melihat slot.</div>';
        return;
    }

    console.log("activeLapanganId =", activeLapanganId);
    console.log("activeDate =", activeDate);
    console.log(allJadwals);
    const slots = allJadwals.filter(j => {
        console.log (
            "DB:",
            j.lapangan_id,
            j.tanggal,
            "| ACTIVE:",
            activeLapanganId,
            activeDate
    );

    return  Number(j.lapangan_id) === Number(activeLapanganId)
            && String(j.tanggal).trim() === String(activeDate).trim();
    });
       
    if (slots.length === 0) {
        grid.innerHTML = '<div class="no-slot">Tidak ada jadwal tersedia pada tanggal ini.</div>';
        return;
    }
    slots.forEach(slot => {
        const isPenuh = slot.status !== 'Tersedia';
        const isDipilih = selectedJadwal && selectedJadwal.id === slot.id;
        const card = document.createElement('div');
        card.className = 'slot-card' + (isPenuh ? ' penuh' : '') + (isDipilih ? ' dipilih' : '');
        let html = '';
        if (slot.ada_promo && !isPenuh) {
            html += `<div class="promo-label">PROMO</div>`;
        }
        html += `<div class="jam">${slot.jam_mulai}-${slot.jam_selesai}</div>`;
        if (slot.ada_promo) {
            html += `<div class="harga-coret">Rp ${formatRp(slot.harga)}</div>`;
            html += `<div class="harga">Rp ${formatRp(slot.harga_promo)}</div>`;
        } else {
            html += `<div class="harga">Rp ${formatRp(slot.harga)}</div>`;
        }
        card.innerHTML = html;
        if (!isPenuh) {
            card.onclick = () => pilihSlot(slot, card);
        }
        grid.appendChild(card);
    });
}

function pilihSlot(slot, el) {
    if (selectedJadwal && selectedJadwal.id === slot.id) {
        selectedJadwal = null;
        el.classList.remove('dipilih');
        resetSummary();
        return;
    }
    selectedJadwal = slot;
    document.querySelectorAll('.slot-card').forEach(c => c.classList.remove('dipilih'));
    el.classList.add('dipilih');
    document.getElementById('selected_jadwal_id').value = slot.id;
    document.getElementById('selectedDetail').classList.add('show');
    document.getElementById('detNama').textContent = slot.nama_lapangan;
    document.getElementById('detHargaAsli').textContent = 'Rp ' + formatRp(slot.ada_promo ? slot.harga_promo : slot.harga);
    document.getElementById('detInfo').textContent = formatTanggal(slot.tanggal) + ' • ' + slot.jam_mulai + '-' + slot.jam_selesai;
    document.getElementById('pricePlaceholder').style.display = 'none';
    document.getElementById('priceBreakdown').style.display = 'block';
    document.getElementById('subtotalVal').textContent = 'Rp ' + formatRp(slot.harga);
    if (slot.ada_promo) {
        const selisih = slot.harga - slot.harga_promo;
        document.getElementById('diskonRow').style.display = 'flex';
        document.getElementById('diskonVal').textContent = '-Rp ' + formatRp(selisih);
        document.getElementById('totalVal').textContent = 'Rp ' + formatRp(slot.harga_promo);
    } else {
        document.getElementById('diskonRow').style.display = 'none';
        document.getElementById('totalVal').textContent = 'Rp ' + formatRp(slot.harga);
    }
    document.getElementById('btnSubmit').disabled = false;
}

function resetSummary() {
    selectedJadwal = null;
    document.getElementById('selected_jadwal_id').value = '';
    document.getElementById('selectedDetail').classList.remove('show');
    document.getElementById('pricePlaceholder').style.display = 'block';
    document.getElementById('priceBreakdown').style.display = 'none';
    document.getElementById('btnSubmit').disabled = true;
}

function formatHP(input) {
    let cursorPos = input.selectionStart;
    let prevLen = input.value.length;
    let val = input.value.replace(/\D/g, '');
    let formatted = '';
    if (val.length > 0) formatted += val.substring(0, 4);
    if (val.length > 4) formatted += ' ' + val.substring(4, 8);
    if (val.length > 8) formatted += ' ' + val.substring(8, 12);
    if (val.length > 12) formatted += ' ' + val.substring(12, 15);
    input.value = formatted;
    let diff = formatted.length - prevLen;
    input.setSelectionRange(cursorPos + diff, cursorPos + diff);
}

function formatRp(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatTanggal(dateStr) {
    const d = new Date(dateStr);
    const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return hari[d.getDay()] + ', ' + d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
}
@endverbatim
</script>

</body>
</html>