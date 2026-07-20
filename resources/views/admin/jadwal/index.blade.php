@extends('layouts.admin')

@section('title', 'Jadwal Ketersediaan')

@push('styles')
<style>
    .filter-wrap {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-wrap input[type="date"] {
        border: 1.5px solid #e0e7ff;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        background: white;
        color: #1a1a2e;
        outline: none;
        transition: 0.2s;
    }
    .filter-wrap input[type="date"]:focus {
        border-color: #1565C0;
        box-shadow: 0 0 0 3px rgba(21,101,192,0.1);
    }
    .btn-filter {
        background: #1565C0;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
    }
    .btn-filter:hover { background: #0d47a1; }

    .jadwal-table-wrap {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow-x: auto;
        margin-bottom: 24px;
    }
    .jadwal-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }
    .jadwal-table th {
        background: #1565C0;
        color: white;
        font-size: 12px;
        font-weight: 600;
        padding: 12px 10px;
        text-align: center;
        white-space: nowrap;
    }
    .jadwal-table th:first-child { text-align: left; padding-left: 20px; }
    .jadwal-table td {
        padding: 10px;
        text-align: center;
        border-bottom: 1px solid #f0f4ff;
        font-size: 13px;
        color: #1a1a2e;
        vertical-align: middle;
    }
    .jadwal-table td:first-child {
        text-align: left;
        padding-left: 20px;
        font-weight: 600;
        white-space: nowrap;
    }
    .jadwal-table tr:last-child td { border-bottom: none; }
    .jadwal-table tr:hover td { background: #f8faff; }

    .slot-cell {
        width: 100%;
        height: 36px;
        border-radius: 6px;
        display: block;
        cursor: pointer;
        transition: 0.2s;
        border: 1.5px solid transparent;
        position: relative;
    }
    .slot-cell.tersedia { background: #bbf7d0; border-color: #22c55e; }
    .slot-cell.tersedia:hover { background: #86efac; }
    .slot-cell.penuh { background: #cbd5e1; border-color: #94a3b8; }
    .slot-cell.penuh:hover { background: #94a3b8; }
    .slot-cell.promo { background: #bbf7d0; border-color: #22c55e; }
    .slot-cell.promo:hover { background: #86efac; }
    .slot-cell.penuh.promo { background: #cbd5e1; border-color: #94a3b8; }
    .slot-cell.penuh.promo:hover { background: #94a3b8; }

    .promo-badge {
        position: absolute;
        top: 3px;
        right: 4px;
        font-size: 9px;
        background: #f59e0b;
        color: white;
        border-radius: 4px;
        padding: 1px 4px;
        font-weight: 700;
        line-height: 1.4;
    }
    .legend {
        display: flex;
        gap: 20px;
        align-items: center;
        margin-bottom: 16px;
        font-size: 13px;
        color: #64748b;
        flex-wrap: wrap;
    }
    .legend span { display: flex; align-items: center; gap: 6px; }
    .legend-box { width: 16px; height: 16px; border-radius: 4px; border: 1.5px solid; }
    .lb-tersedia { background: #bbf7d0; border-color: #22c55e; }
    .lb-penuh    { background: #cbd5e1; border-color: #94a3b8; }
    .lb-promo    { background: #fffbeb; border-color: #fcd34d; }

    .lapangan-tab-btn {
        padding: 8px 18px;
        border-radius: 20px;
        border: 1.5px solid #e0e7ff;
        background: white;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        cursor: pointer;
        transition: 0.2s;
    }
    .lapangan-tab-btn:hover { border-color: #1565C0; color: #1565C0; }
    .lapangan-tab-btn.active { background: #1565C0; border-color: #1565C0; color: white; }

    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
        font-size: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .no-data i { font-size: 40px; margin-bottom: 12px; display: block; }

    /* MODAL */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 999;
    }
    .modal-overlay.show { display: block; }
    .modal-box {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.18);
        width: 340px;
        max-width: 95vw;
        overflow: hidden;
        animation: popIn 0.15s ease;
        position: fixed;
        z-index: 1000;
    }
    @keyframes popIn {
        from { transform: scale(0.92); opacity: 0; }
        to   { transform: scale(1);    opacity: 1; }
    }
    .modal-header {
        background: #1565C0;
        color: white;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-header h3 { font-size: 14px; font-weight: 700; margin: 0; }
    .modal-close {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 26px; height: 26px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        display: flex; align-items: center; justify-content: center;
        transition: 0.2s;
    }
    .modal-close:hover { background: rgba(255,255,255,0.35); }
    .modal-body { padding: 16px 18px; }
    .modal-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 0;
        font-size: 13px;
        border-bottom: 1px solid #f0f4ff;
    }
    .modal-row:last-child { border-bottom: none; }
    .modal-row .mlabel { color: #64748b; }
    .modal-row .mvalue { font-weight: 600; color: #1a1a2e; text-align: right; }
    .modal-divider {
        font-size: 11px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 0 4px;
    }
    .harga-coret { text-decoration: line-through; color: #94a3b8; font-size: 12px; }

    .badge-tersedia  { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #2e7d32; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-penuh     { background: #f1f5f9; color: #475569; border: 1.5px solid #cbd5e1; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-berhasil  { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #2e7d32; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-tertunda  { background: #fff8e1; color: #f57f17; border: 1.5px solid #f57f17; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-dibatalkan { background: #fce4ec; color: #c62828; border: 1.5px solid #c62828; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    
</style>
@endpush

@section('content')

<div class="page-header">
    <h1>Jadwal Ketersediaan</h1>
    <p>Lihat jadwal ketersediaan lapangan berdasarkan tanggal dan lapangan.</p>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('admin.jadwal.index') }}" id="filterForm">
    <div class="filter-wrap">
        <input type="date" name="tanggal" value="{{ $tanggal }}"
            onchange="document.getElementById('filterForm').submit()">
        <select name="lapangan_id" onchange="document.getElementById('filterForm').submit()"
            style="border:1.5px solid #e0e7ff; border-radius:10px; padding:10px 36px 10px 14px; font-size:14px; background:white; color:#1a1a2e; outline:none; appearance:none; -webkit-appearance:none; background-image:url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2712%27 height=%2712%27 viewBox=%270 0 12 12%27%3E%3Cpath fill=%27%2364748b%27 d=%27M6 8L1 3h10z%27/%3E%3C/svg%3E'); background-repeat:no-repeat; background-position:right 12px center;">
            <option value="">Semua Lapangan</option>
            @foreach($lapangans as $lap)
                <option value="{{ $lap->id }}" {{ $lapanganId == $lap->id ? 'selected' : '' }}>
                    {{ $lap->nama_lapangan }}
                </option>
            @endforeach
        </select>
    </div>
</form>


{{-- LEGEND --}}
<div class="legend">
    <span><span class="legend-box lb-tersedia"></span> Tersedia</span>
    <span><span class="legend-box lb-penuh"></span> Penuh</span>
    <span style="display:flex;align-items:center;gap:6px;">
        <span style="background:#f59e0b;color:white;font-size:10px;font-weight:700;padding:1px 6px;border-radius:4px;">%</span> Promo
    </span>
</div>

{{-- MODAL POPUP --}}
<div class="modal-overlay" id="modalOverlay" onclick="cekTutupModal(event)"></div>
<div class="modal-box" id="modalBox" style="display:none;">
    <div class="modal-header">
        <h3 id="modalTitle">Detail Slot</h3>
        <button class="modal-close" onclick="tutupModal()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="modal-body">
        <div class="modal-divider">Info Slot</div>
        <div class="modal-row">
            <span class="mlabel">Status</span>
            <span class="mvalue" id="mStatus">-</span>
        </div>
        <div class="modal-row">
            <span class="mlabel">Lapangan</span>
            <span class="mvalue" id="mLapangan">-</span>
        </div>
        <div class="modal-row">
            <span class="mlabel">Tanggal</span>
            <span class="mvalue" id="mTanggal">-</span>
        </div>
        <div class="modal-row">
            <span class="mlabel">Jam</span>
            <span class="mvalue" id="mJam">-</span>
        </div>
        <div class="modal-row">
            <span class="mlabel">Durasi</span>
            <span class="mvalue" id="mDurasi">-</span>
        </div>
        <div class="modal-row">
            <span class="mlabel">Harga</span>
            <span class="mvalue" id="mHarga">-</span>
        </div>
        <div class="modal-row" id="mPromoRow" style="display:none">
            <span class="mlabel">Promo</span>
            <span class="mvalue" id="mPromo">-</span>
        </div>
        <div id="mPemesanSection">
            <div class="modal-divider">Dipesan Oleh</div>
            <div class="modal-row">
                <span class="mlabel">Nama</span>
                <span class="mvalue" id="mNama">-</span>
            </div>
            <div class="modal-row">
                <span class="mlabel">No HP</span>
                <span class="mvalue" id="mHP">-</span>
            </div>
            <div class="modal-row">
                <span class="mlabel">Kode Booking</span>
                <span class="mvalue" id="mKode">-</span>
            </div>
            <div class="modal-row">
                <span class="mlabel">Status Bayar</span>
                <span class="mvalue" id="mStatusBayar">-</span>
            </div>
        </div>
    </div>
</div>
{{-- TABEL GRID --}}
@if($jadwalByLapangan->isEmpty())
    <div class="jadwal-table-wrap">
        <div class="no-data">
            <i class="fas fa-calendar-xmark"></i>
            Tidak ada jadwal pada tanggal ini.
        </div>
    </div>
@else
    @php
        // Header per 60 menit berdasarkan range jam terluas dari semua lapangan
        $jamPertama = $jadwals->min('jam_mulai');
        $jamTerakhir = $jadwals->max('jam_selesai');
        $semuaJam = collect();
        $cursor = \Carbon\Carbon::parse($jamPertama);
        $akhir  = \Carbon\Carbon::parse($jamTerakhir);
        while ($cursor->copy()->addMinutes(60)->lte($akhir)) {
            $semuaJam->push($cursor->format('H:i').'–'.$cursor->copy()->addMinutes(60)->format('H:i'));
            $cursor->addMinutes(60);
        }
    @endphp

    <div class="jadwal-table-wrap">
        <table class="jadwal-table">
            <thead>
                <tr>
                    <th>Lapangan</th>
                    @foreach($semuaJam as $jam)
                        <th>{{ $jam }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalByLapangan as $lapId => $jadwalList)
                    @php $namaLapangan = $jadwalList->first()->lapangan->nama_lapangan; @endphp
                    <tr>
                        <td>{{ $namaLapangan }}</td>
                        @php $skipKolom = 0; @endphp
                        @foreach($semuaJam as $jam)
                            @if($skipKolom > 0)
                                @php $skipKolom--; @endphp
                                @continue
                            @endif
                            @php
                                [$jMulai] = explode('–', $jam);
                                $slot = $jadwalList->first(fn($j) => substr($j->jam_mulai,0,5) === $jMulai);

                                $colspan = 1;
                                if ($slot) {
                                    $menitMulai   = (int)substr($slot->jam_mulai,0,2)*60 + (int)substr($slot->jam_mulai,3,2);
                                    $menitSelesai = (int)substr($slot->jam_selesai,0,2)*60 + (int)substr($slot->jam_selesai,3,2);
                                    $durasi       = $menitSelesai - $menitMulai;
                                    $colspan      = intval($durasi / 60);
                                    $skipKolom    = $colspan - 1;

                                    $isPenuh = $slot->status_jadwal === 'Penuh';
                                    $promo = $slot->lapangan->promos
                                        ->filter(function($p) use ($slot) {
                                            $jamSlot = substr($slot->jam_mulai, 0, 5) . '-' . substr($slot->jam_selesai, 0, 5);
                                            $slots   = is_array($p->pivot->slots)
                                                ? $p->pivot->slots
                                                : json_decode($p->pivot->slots, true) ?? [];
                                            return $p->status_promo
                                                && \Carbon\Carbon::parse($p->tanggal_mulai)->lte(\Carbon\Carbon::parse($slot->tanggal_jadwal))
                                                && \Carbon\Carbon::parse($p->tanggal_berakhir)->gte(\Carbon\Carbon::parse($slot->tanggal_jadwal))
                                                && in_array($jamSlot, $slots);
                                        })->first();

                                    $harga = $slot->lapangan->jenisLapangan->harga_per_jam ?? 0;
                                    $hargaPromo = $promo ? $harga - ($harga * $promo->diskon_persen / 100) : null;
                                    $booking    = $slot->bookings->first();

                                    $cssClass = $isPenuh ? 'penuh' : 'tersedia';
                                    if ($promo) $cssClass .= ' promo';

                                    $data = [
                                        'lapangan'     => $namaLapangan,
                                        'tanggal'      => \Carbon\Carbon::parse($slot->tanggal_jadwal)->isoFormat('D MMMM YYYY'),
                                        'jam'          => substr($slot->jam_mulai,0,5).' - '.substr($slot->jam_selesai,0,5),
                                        'durasi'       => $durasi.' Menit',
                                        'status'       => $slot->status_jadwal,
                                        'harga'        => $harga,
                                        'harga_promo'  => $hargaPromo,
                                        'promo_nama'   => $promo ? $promo->nama_promo.' (Diskon '.$promo->diskon_persen.'%)' : null,
                                        'nama'         => $booking?->pelanggan?->nama_pelanggan,
                                        'nomor_hp'     => $booking?->pelanggan?->nomor_hp,
                                        'kode_booking' => $booking?->kode_booking,
                                        'status_bayar' => $booking?->status,
                                    ];
                                }
                            @endphp
                            <td colspan="{{ $colspan }}" style="padding: 4px 6px;">
                                @if($slot)
                                    <div class="slot-cell {{ $cssClass }}"
                                        onclick='tampilModal(event, @json($data))'
                                        title="{{ $slot->status_jadwal }}">
                                        @if($promo)
                                            <span class="promo-badge">%</span>
                                        @endif
                                    </div>
                                @else
                                    <span style="color:#e2e8f0;font-size:18px;">—</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection

@push('scripts')
<script>
function tampilModal(event, data) {
    const statusMap = {
        'Tersedia': '<span class="badge-tersedia">Tersedia</span>',
        'Penuh':    '<span class="badge-penuh">Penuh</span>',
    };
    const bayarMap = {
        'Berhasil':   '<span class="badge-berhasil">Berhasil</span>',
        'Tertunda':   '<span class="badge-tertunda">Tertunda</span>',
        'Dibatalkan': '<span class="badge-dibatalkan">Dibatalkan</span>',
    };

    const modal   = document.getElementById('modalBox');
    const overlay = document.getElementById('modalOverlay');
    overlay.classList.add('show');
    modal.style.display = 'block';

    const rect   = event.currentTarget.getBoundingClientRect();
    const modalW = 340;
    const modalH = modal.offsetHeight || 320;
    const winW   = window.innerWidth;
    const winH   = window.innerHeight;

    let left = rect.left;
    let top  = rect.bottom + 8;

    if (left + modalW > winW - 12) left = winW - modalW - 12;
    if (top + modalH > winH - 12) top = rect.top - modalH - 8;

    modal.style.left = left + 'px';
    modal.style.top  = top  + 'px';

    document.getElementById('modalTitle').textContent  = data.lapangan + ' | ' + data.jam;
    document.getElementById('mStatus').innerHTML       = statusMap[data.status] || data.status;
    document.getElementById('mLapangan').textContent   = data.lapangan;
    document.getElementById('mTanggal').textContent    = data.tanggal;
    document.getElementById('mJam').textContent        = data.jam;
    document.getElementById('mDurasi').textContent     = data.durasi;

    if (data.harga_promo) {
        document.getElementById('mHarga').innerHTML =
            '<span class="harga-coret">Rp ' + formatRp(data.harga) + '</span> → Rp ' + formatRp(data.harga_promo);
    } else {
        document.getElementById('mHarga').textContent = 'Rp ' + formatRp(data.harga);
    }

    if (data.promo_nama) {
        document.getElementById('mPromoRow').style.display = 'flex';
        document.getElementById('mPromo').textContent = data.promo_nama;
    } else {
        document.getElementById('mPromoRow').style.display = 'none';
    }

    const pemesanSection = document.getElementById('mPemesanSection');
    if (data.status === 'Penuh' && data.nama) {
        pemesanSection.style.display = 'block';
        document.getElementById('mNama').textContent      = data.nama;
        document.getElementById('mHP').textContent        = data.nomor_hp;
        document.getElementById('mKode').textContent      = data.kode_booking;
        document.getElementById('mStatusBayar').innerHTML = bayarMap[data.status_bayar] || data.status_bayar;
    } else {
        pemesanSection.style.display = 'none';
    }
}

function tutupModal() {
    document.getElementById('modalOverlay').classList.remove('show');
    document.getElementById('modalBox').style.display = 'none';
}

function cekTutupModal(e) {
    if (e.target === document.getElementById('modalOverlay')) tutupModal();
}

function formatRp(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function pilihLapangan(id) {
    document.getElementById('hiddenLapanganId').value = id;
    document.getElementById('filterForm').submit();
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupModal();
});
</script>
@endpush