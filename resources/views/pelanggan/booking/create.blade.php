<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <title>Booking Lapangan - Bintang Sport</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
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
            padding: 0 32px;
            width: 100%;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
        }
        .topbar-logo img { height: 38px; width: auto; display: block; }
        .topbar-title {
            font-size: 16px; font-weight: 700; color: #10275b;
            position: absolute; left: 50%; transform: translateX(-50%);
        }
        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600; color: #475569;
            background: #f1f5f9; border-radius: 8px; padding: 8px 16px;
            text-decoration: none; transition: 0.2s;
        }
        .btn-back:hover { background: #e2e8f0; color: #10275b; }

        .page-wrapper {
            max-width: 1100px;
            margin: 84px auto 60px;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 28px;
            align-items: start;
        }

        .left-title { font-size: 22px; font-weight: 700; color: #03045e; margin-bottom: 4px; }
        .left-sub { color: #64748b; font-size: 14px; margin-bottom: 24px; }

        .calendar-wrap {
            background: white; border-radius: 14px;
            border: 1.5px solid #e2e8f0; padding: 20px; margin-bottom: 20px;
        }
        .cal-header {
            display: flex; align-items: center;
            justify-content: space-between; margin-bottom: 16px;
        }
        .cal-title { font-size: 15px; font-weight: 700; color: #03045e; }
        .cal-nav { display: flex; gap: 6px; }
        .cal-nav button {
            width: 30px; height: 30px; border-radius: 50%;
            border: 1.5px solid #e2e8f0; background: white;
            cursor: pointer; display: flex; align-items: center;
            justify-content: center; color: #475569; font-size: 14px; transition: 0.2s;
        }
        .cal-nav button:hover { background: #f1f5f9; }
        .cal-grid {
            display: grid; grid-template-columns: repeat(7, 1fr);
            gap: 4px; text-align: center;
        }
        .cal-day-name {
            font-size: 11px; font-weight: 600; color: #94a3b8;
            text-transform: uppercase; padding: 4px 0;
        }
        .cal-day {
            padding: 8px 4px; border-radius: 8px; font-size: 13px;
            font-weight: 600; color: #10275b; cursor: pointer;
            transition: 0.15s; border: 1.5px solid transparent;
        }
        .cal-day:hover:not(.empty):not(.past) { border-color: #0052cc; color: #0052cc; }
        .cal-day.empty { cursor: default; }
        .cal-day.past { color: #cbd5e1; cursor: not-allowed; }
        .cal-day.active { background: #0052cc; color: white !important; border-color: #0052cc; }
        .cal-day.today:not(.active) { border-color: #0052cc; color: #0052cc; }

        .slot-warning {
            background: #fef3c7; color: #d97706; border: 1px solid #fcd34d;
            border-radius: 8px; padding: 10px 14px; font-size: 13px;
            font-weight: 600; margin-bottom: 12px; display: none;
            align-items: center; gap: 8px;
        }
        .slot-warning.show { display: flex; }

        .slot-panel {
            background: white; border-radius: 14px;
            border: 1.5px solid #e2e8f0; padding: 20px;
        }
        .slot-header {
            display: flex; align-items: center;
            justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;
        }
        .slot-header h3 { font-size: 15px; font-weight: 700; color: #03045e; }
        .legend { display: flex; gap: 14px; align-items: center; font-size: 12px; color: #64748b; flex-wrap: wrap; }
        .legend span { display: flex; align-items: center; gap: 5px; }
        .dot { width: 11px; height: 11px; border-radius: 50%; display: inline-block; }
        .dot-tersedia { border: 1.5px solid #cbd5e1; background: white; }
        .dot-penuh { background: #cbd5e1; }
        .dot-dipilih { background: #0052cc; }

        .slot-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .slot-card {
            border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 12px 10px;
            text-align: center; cursor: pointer; transition: 0.2s; background: white;
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
        .no-slot { text-align: center; padding: 40px 20px; color: #94a3b8; font-size: 14px; grid-column: 1/-1; }

        .summary-card {
            background: white; border-radius: 16px;
            border: 1.5px solid #e2e8f0; padding: 24px; position: sticky; top: 84px;
        }
        .summary-card h2 { font-size: 17px; font-weight: 700; color: #03045e; margin-bottom: 20px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 6px; }
        .form-group input {
            width: 100%; border: 1.5px solid #cbd5e1; border-radius: 8px;
            padding: 11px 14px; font-size: 14px; outline: none;
            transition: border 0.2s, box-shadow 0.2s; color: #10275b;
        }
        .form-group input:focus { border-color: #0052cc; box-shadow: 0 0 0 3px rgba(0,82,204,0.12); }
        .form-group input.is-invalid { border-color: #dc2626; }
        .error-msg { color: #dc2626; font-size: 12px; margin-top: 4px; display: block; }

        .selected-detail {
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 14px; margin-bottom: 16px; display: none;
        }
        .selected-detail.show { display: block; }
        .det-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px; }
        .det-nama { font-size: 14px; font-weight: 700; color: #03045e; display: flex; align-items: center; gap: 8px; }
        .det-nama i { color: #0052cc; }
        .det-harga { font-size: 14px; font-weight: 700; color: #0052cc; }
        .det-info { font-size: 12px; color: #64748b; margin-top: 2px; }

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

        @media (max-width: 900px) {
            .page-wrapper { grid-template-columns: 1fr; }
            .summary-card { position: static; }
        }
        @media (max-width: 700px) {
            .topbar { padding: 0 16px; }
            .slot-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

<div class="topbar">
    <a href="{{ url('/beranda') }}" class="topbar-logo">
        <img src="{{ asset('img/logo.png') }}" alt="Bintang Sport" />
    </a>
    <div class="topbar-title">Booking Lapangan</div>
    <a href="{{ route('jadwal.public') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
@csrf
<div id="hidden_jadwal_inputs"></div>

<div class="page-wrapper">

    {{-- KIRI --}}
    <div>
        <h1 class="left-title" id="pageTitle">Jadwal Lapangan</h1>
        <p class="left-sub">Pilih tanggal dan waktu yang tersedia.</p>

        <div class="calendar-wrap">
            <div class="cal-header">
                <div class="cal-title" id="calTitle"></div>
                <div class="cal-nav">
                    <button type="button" onclick="geserBulan(-1)"><i class="bi bi-chevron-left"></i></button>
                    <button type="button" onclick="geserBulan(1)"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div class="cal-grid" id="calGrid"></div>
        </div>

        <div class="slot-panel">
            <div class="slot-header">
                <h3>Ketersediaan Lapangan</h3>
                <div class="legend">
                    <span><span class="dot dot-tersedia"></span> Tersedia</span>
                    <span><span class="dot dot-penuh"></span> Penuh</span>
                    <span><span class="dot dot-dipilih"></span> Dipilih</span>
                </div>
            </div>
            <div class="slot-warning" id="slotWarning">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span id="slotWarningMsg"></span>
            </div>
            <div class="slot-grid" id="slotGrid">
                <div class="no-slot">Pilih tanggal untuk melihat slot.</div>
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
                    inputmode="numeric" autocomplete="off" maxlength="15"
                    class="{{ $errors->has('nomor_hp') ? 'is-invalid' : '' }}"
                    oninput="formatHP(this)">
                @error('nomor_hp')
                    <small class="error-msg">{{ $message }}</small>
                @enderror
            </div>

            <div class="selected-detail" id="selectedDetail">
                <div class="det-top">
                    <span class="det-nama">
                        <i class="bi bi-geo-alt-fill"></i>
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
                        <span>Diskon Promo</span>
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
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>
    </div>

</div>
</form>

<script>
const allJadwals = @json($jadwalData);

let activeDate    = null;
let selectedSlots = [];
let calYear       = new Date().getFullYear();
let calMonth      = new Date().getMonth();

const HARI_NAMA  = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
const BULAN_NAMA = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

window.addEventListener('DOMContentLoaded', renderCalendar);

function geserBulan(arah) {
    calMonth += arah;
    if (calMonth > 11) { calMonth = 0; calYear++; }
    if (calMonth < 0)  { calMonth = 11; calYear--; }
    renderCalendar();
}

function renderCalendar() {
    document.getElementById('calTitle').textContent = BULAN_NAMA[calMonth] + ' ' + calYear;
    const grid = document.getElementById('calGrid');
    grid.innerHTML = '';

    HARI_NAMA.forEach(h => {
        const el = document.createElement('div');
        el.className = 'cal-day-name';
        el.textContent = h;
        grid.appendChild(el);
    });

    const firstDay = new Date(calYear, calMonth, 1).getDay();
    const totalDay = new Date(calYear, calMonth + 1, 0).getDate();
    const today    = new Date(); today.setHours(0,0,0,0);

    for (let i = 0; i < firstDay; i++) {
        const el = document.createElement('div');
        el.className = 'cal-day empty';
        grid.appendChild(el);
    }

    for (let d = 1; d <= totalDay; d++) {
        const date    = new Date(calYear, calMonth, d);
        const dateStr = date.toISOString().split('T')[0];
        const isPast  = date < today;
        const isToday = date.getTime() === today.getTime();

        const el = document.createElement('div');
        el.textContent = d;
        el.className = 'cal-day' +
            (isPast ? ' past' : '') +
            (isToday ? ' today' : '') +
            (activeDate === dateStr ? ' active' : '');

        if (!isPast) el.onclick = () => pilihTanggal(dateStr, el);
        grid.appendChild(el);
    }
}

function pilihTanggal(dateStr, el) {
    activeDate    = dateStr;
    selectedSlots = [];
    document.querySelectorAll('.cal-day').forEach(d => d.classList.remove('active'));
    el.classList.add('active');
    renderSlots();
    resetSummary();
    hideWarning();
}

function renderSlots() {
    const grid = document.getElementById('slotGrid');
    grid.innerHTML = '';
    hideWarning();

    if (!activeDate) {
        grid.innerHTML = '<div class="no-slot">Pilih tanggal untuk melihat slot.</div>';
        return;
    }

    const slots = allJadwals.filter(j => String(j.tanggal).trim() === String(activeDate).trim());

    if (slots.length === 0) {
        grid.innerHTML = '<div class="no-slot">Tidak ada jadwal tersedia pada tanggal ini.</div>';
        return;
    }

    slots.forEach(slot => {
        const isPenuh   = slot.status !== 'Tersedia';
        const isDipilih = selectedSlots.some(s => s.id === slot.id);
        const card      = document.createElement('div');
        card.className  = 'slot-card' + (isPenuh ? ' penuh' : '') + (isDipilih ? ' dipilih' : '');

        let html = '';
        if (slot.ada_promo && !isPenuh) html += `<div class="promo-label">PROMO</div>`;
        html += `<div class="jam">${slot.jam_mulai}-${slot.jam_selesai}</div>`;
        if (slot.ada_promo) {
            html += `<div class="harga-coret">Rp ${formatRp(slot.harga)}</div>`;
            html += `<div class="harga">Rp ${formatRp(slot.harga_promo)}</div>`;
        } else {
            html += `<div class="harga">Rp ${formatRp(slot.harga)}</div>`;
        }
        card.innerHTML = html;
        if (!isPenuh) card.onclick = () => pilihSlot(slot, card);
        grid.appendChild(card);
    });
}

function pilihSlot(slot, el) {
    const idx = selectedSlots.findIndex(s => s.id === slot.id);

    if (idx !== -1) {
        if (idx === selectedSlots.length - 1) {
            selectedSlots.pop();
            el.classList.remove('dipilih');
            updateHiddenInputs();
            updateSummary();
            hideWarning();
        } else {
            showWarning('Hapus slot dari urutan terakhir terlebih dahulu.');
        }
        return;
    }

    if (selectedSlots.length > 0) {
        const last = selectedSlots[selectedSlots.length - 1];
        if (last.jam_selesai !== slot.jam_mulai) {
            showWarning('Slot harus berurutan. Pilih slot yang berdekatan dengan slot terakhir.');
            return;
        }
    }

    selectedSlots.push(slot);
    el.classList.add('dipilih');
    updateHiddenInputs();
    updateSummary();
    hideWarning();
}

function showWarning(msg) {
    const el = document.getElementById('slotWarning');
    document.getElementById('slotWarningMsg').textContent = msg;
    el.classList.add('show');
    setTimeout(() => el.classList.remove('show'), 3000);
}

function hideWarning() {
    document.getElementById('slotWarning').classList.remove('show');
}

function updateHiddenInputs() {
    const container = document.getElementById('hidden_jadwal_inputs');
    container.innerHTML = '';
    selectedSlots.forEach(slot => {
        const input = document.createElement('input');
        input.type  = 'hidden';
        input.name  = 'jadwal_ids[]';
        input.value = slot.id;
        container.appendChild(input);
    });
}

function updateSummary() {
    if (selectedSlots.length === 0) { resetSummary(); return; }

    const first = selectedSlots[0];
    const last  = selectedSlots[selectedSlots.length - 1];

    document.getElementById('selectedDetail').classList.add('show');
    document.getElementById('detNama').textContent = first.nama_lapangan;
    document.getElementById('detInfo').textContent =
        formatTanggal(first.tanggal) + ' • ' + first.jam_mulai + '-' + last.jam_selesai +
        (selectedSlots.length > 1 ? ' (' + selectedSlots.length + ' slot)' : '');

    let subtotal = 0, totalSetelahDiskon = 0, adaPromo = false;
    selectedSlots.forEach(slot => {
        subtotal += slot.harga;
        totalSetelahDiskon += slot.ada_promo ? slot.harga_promo : slot.harga;
        if (slot.ada_promo) adaPromo = true;
    });

    document.getElementById('detHargaAsli').textContent        = 'Rp ' + formatRp(totalSetelahDiskon);
    document.getElementById('pricePlaceholder').style.display  = 'none';
    document.getElementById('priceBreakdown').style.display    = 'block';
    document.getElementById('subtotalVal').textContent         = 'Rp ' + formatRp(subtotal);

    if (adaPromo) {
        document.getElementById('diskonRow').style.display = 'flex';
        document.getElementById('diskonVal').textContent   = '-Rp ' + formatRp(subtotal - totalSetelahDiskon);
    } else {
        document.getElementById('diskonRow').style.display = 'none';
    }

    document.getElementById('totalVal').textContent   = 'Rp ' + formatRp(totalSetelahDiskon);
    document.getElementById('btnSubmit').disabled     = false;
}

function resetSummary() {
    selectedSlots = [];
    document.getElementById('hidden_jadwal_inputs').innerHTML  = '';
    document.getElementById('selectedDetail').classList.remove('show');
    document.getElementById('pricePlaceholder').style.display  = 'block';
    document.getElementById('priceBreakdown').style.display    = 'none';
    document.getElementById('btnSubmit').disabled = true;
}

function formatHP(input) {
    let cursorPos = input.selectionStart;
    let prevLen   = input.value.length;
    let val       = input.value.replace(/\D/g, '');
    let formatted = '';
    if (val.length > 0)  formatted += val.substring(0, 4);
    if (val.length > 4)  formatted += ' ' + val.substring(4, 8);
    if (val.length > 8)  formatted += ' ' + val.substring(8, 12);
    if (val.length > 12) formatted += ' ' + val.substring(12, 15);
    input.value = formatted;
    let diff = formatted.length - prevLen;
    input.setSelectionRange(cursorPos + diff, cursorPos + diff);
}

function formatRp(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function formatTanggal(dateStr) {
    const d     = new Date(dateStr);
    const hari  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    return hari[d.getDay()] + ', ' + d.getDate() + ' ' + bulan[d.getMonth()] + ' ' + d.getFullYear();
}
</script>

</body>
</html>