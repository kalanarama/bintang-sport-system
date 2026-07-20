@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px 16px;     
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        display: flex;
        align-items: center;
        gap: 12px;               
        min-width: 0;            
    }
    .stat-icon {
        width: 48px; height: 48px;  
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;            
    }


    .stat-icon.blue   { background: #e8f0fe; color: #1565C0; }
    .stat-icon.green  { background: #e8f5e9; color: #2e7d32; }
    .stat-icon.orange { background: #fff3e0; color: #e65100; }
    .stat-icon.purple { background: #f3e5f5; color: #6a1b9a; }
    .stat-label { 
        font-size: 10px;          
        color: #64748b; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        margin-bottom: 4px;
        word-break: break-word;   
    }
    .stat-value { 
        font-size: 22px;            
        font-weight: 800; 
        color: #1a1a2e;
        word-break: break-all;      
    }

    .section-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        overflow: visible; 
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .section-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f0f4ff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .section-card-header h3 { font-size: 15px; font-weight: 700; color: #1a1a2e; margin: 0; }
    .section-card-body { 
        padding: 24px; 
        overflow: visible;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;  
    }

    /* Chart bars */
    .chart-wrap {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        height: 200px;             
        padding-bottom: 0;
        padding-right: 8px;
        flex: 1;
        border-bottom: none;      
        margin-bottom: 0;
    }
    .chart-col {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        height: 100%;
        justify-content: flex-end;
    }
    .chart-bar {
        width: 100%;
        background: #1565C0;
        border-radius: 6px 6px 0 0;
        min-height: 4px;
        transition: 0.3s;
    }
    .chart-bar:hover { background: #0d47a1; }
    .chart-label { font-size: 11px; color: #94a3b8; font-weight: 600; }

    /* Donut chart */
    .donut-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
    }
    .donut-svg { 
        width: 160px; 
        height: 160px; 
        overflow: visible; 
    }
    .donut-legend { width: 100%; }
    .legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 6px 0;
        font-size: 13px;
    }
    .legend-dot {
        width: 10px; height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    .badge-status {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-berhasil  { background: #e8f5e9; color: #2e7d32; border: 1.5px solid #2e7d32; }
    .badge-tertunda  { background: #fff8e1; color: #f57f17; border: 1.5px solid #f57f17; }
    .badge-dibatalkan { background: #fce4ec; color: #c62828; border: 1.5px solid #c62828; }

    .table thead tr th:first-child { border-radius: 10px 0 0 0 !important; }
    .table thead tr th:last-child  { border-radius: 0 10px 0 0 !important; }
    
</style>
@endpush

@section('content')
@if(session('success'))
<div id="alertSuccess" style="
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    background: #ecfdf5;
    border: 1.5px solid #34d399;
    border-radius: 12px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 4px 20px rgba(52,211,153,0.2);
    animation: slideIn 0.3s ease;
">
    <i class="fas fa-circle-check" style="color:#10b981;font-size:18px;"></i>
    <span style="font-size:14px;font-weight:600;color:#065f46;">{{ session('success') }}</span>
</div>

<style>
@keyframes slideIn {
    from { transform: translateX(100px); opacity: 0; }
    to   { transform: translateX(0); opacity: 1; }
}
</style>

<script>
setTimeout(() => {
    const el = document.getElementById('alertSuccess');
    if (el) el.style.display = 'none';
}, 3000);
</script>
@endif

<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang di Sistem Booking Bintang Sport Center</p>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-3" style="min-width: 0;">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
            <div>
                <div class="stat-label">Booking Hari Ini</div>
                <div class="stat-value">{{ $bookingHariIni }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="min-width: 0;">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fas fa-money-bill-wave"></i></div>
            <div>
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-value">Rp{{ number_format($pendapatanHariIni, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="min-width: 0;">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="fas fa-table-tennis-paddle-ball"></i></div>
            <div>
                <div class="stat-label">Lapangan Aktif</div>
                <div class="stat-value">{{ $lapanganAktif }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3" style="min-width: 0;">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fas fa-tag"></i></div>
            <div>
                <div class="stat-label">Promo Aktif</div>
                <div class="stat-value">{{ $promoAktif }}</div>
            </div>
        </div>
    </div>
</div>

{{-- CHART + DONUT --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="section-card">
            <div class="section-card-header">
                <h3>Statistik Booking (7 Hari Terakhir)</h3>
            </div>
            <div class="section-card-body">
                @php $maxVal = max($statsMingguan->max('total'), 5); @endphp
                <div class="chart-wrap">
                    @foreach($statsMingguan as $stat)
                        <div class="chart-col">
                            <div class="chart-bar"
                                style="height: {{ ($stat['total'] / $maxVal) * 100 }}%"
                                title="{{ $stat['total'] }} booking">
                            </div>
                            <div class="chart-label">{{ $stat['tanggal'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="section-card h-100">
            <div class="section-card-header">
                <h3>Status Booking Hari Ini</h3>
            </div>
            <div class="section-card-body">
                @php
                    $total     = $bookingHariIni ?: 1;
                    $berhasil  = $statusHariIni['berhasil'];
                    $tertunda  = $statusHariIni['tertunda'];
                    $batal     = $statusHariIni['dibatalkan'];

                    $r = 54;
                    $circ = 2 * M_PI * $r;

                    $pBerhasil = ($berhasil / $total) * $circ;
                    $pTertunda = ($tertunda / $total) * $circ;
                    $pBatal    = ($batal    / $total) * $circ;

                    $oBerhasil = 0;
                    $oTertunda = $circ - $pBerhasil;
                    $oBatal    = $circ - $pBerhasil - $pTertunda;
                @endphp
                <div class="donut-wrap">
                    <svg class="donut-svg" viewBox="0 0 120 120">
                        <circle cx="60" cy="60" r="{{ $r }}" fill="none" stroke="#f0f4ff" stroke-width="14"/>
                        <circle cx="60" cy="60" r="{{ $r }}" fill="none" stroke="#1565C0" stroke-width="14"
                            stroke-dasharray="{{ $pBerhasil }} {{ $circ - $pBerhasil }}"
                            stroke-dashoffset="{{ $circ * 0.25 }}"
                            transform="rotate(-90 60 60)"/>
                        <circle cx="60" cy="60" r="{{ $r }}" fill="none" stroke="#f59e0b" stroke-width="14"
                            stroke-dasharray="{{ $pTertunda }} {{ $circ - $pTertunda }}"
                            stroke-dashoffset="{{ $circ * 0.25 - $pBerhasil }}"
                            transform="rotate(-90 60 60)"/>
                        <circle cx="60" cy="60" r="{{ $r }}" fill="none" stroke="#ef4444" stroke-width="14"
                            stroke-dasharray="{{ $pBatal }} {{ $circ - $pBatal }}"
                            stroke-dashoffset="{{ $circ * 0.25 - $pBerhasil - $pTertunda }}"
                            transform="rotate(-90 60 60)"/>
                        <text x="60" y="55" text-anchor="middle" font-size="18" font-weight="800" fill="#1a1a2e">{{ $bookingHariIni }}</text>
                        <text x="60" y="70" text-anchor="middle" font-size="9" fill="#64748b">Total</text>
                    </svg>
                    <div class="donut-legend w-100">
                        <div class="legend-item">
                            <span><span class="legend-dot" style="background:#1565C0"></span>Berhasil</span>
                            <strong>{{ $berhasil }}</strong>
                        </div>
                        <div class="legend-item">
                            <span><span class="legend-dot" style="background:#f59e0b"></span>Tertunda</span>
                            <strong>{{ $tertunda }}</strong>
                        </div>
                        <div class="legend-item">
                            <span><span class="legend-dot" style="background:#ef4444"></span>Dibatalkan</span>
                            <strong>{{ $batal }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL BOOKING TERBARU --}}
<div class="section-card" style="height:fit-content;">
    <div class="section-card-header">
        <h3>Booking Terbaru</h3>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="border-radius: 0;">No</th>
                    <th>Kode Booking</th>
                    <th>Tanggal Main</th>
                    <th>Lapangan</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th style="border-radius: 0;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookingTerbaru as $i => $booking)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $booking->kode_booking }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($booking->jadwal->tanggal_jadwal)->format('d M Y') }}</td>
                    <td>{{ $booking->jadwal->lapangan->nama_lapangan ?? '-' }}</td>
                    <td>{{ $booking->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>Rp{{ number_format($booking->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        @if($booking->status === 'Berhasil')
                            <span class="badge-status badge-berhasil">Berhasil</span>
                        @elseif($booking->status === 'Tertunda')
                            <span class="badge-status badge-tertunda">Tertunda</span>
                        @else
                            <span class="badge-status badge-dibatalkan">Dibatalkan</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Belum ada booking.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 d-flex align-items-center justify-content-between">
    <div style="font-size:13px; color:#64748b;">
        Menampilkan {{ $bookingTerbaru->firstItem() }}-{{ $bookingTerbaru->lastItem() }} dari {{ $bookingTerbaru->total() }} data
    </div>
    <div style="display:flex; gap:6px; align-items:center;">
        @if($bookingTerbaru->onFirstPage())
            <span style="padding:6px 12px; border-radius:8px; border:1.5px solid #e2e8f0; color:#cbd5e1; font-size:13px; font-weight:600;">‹</span>
        @else
            <a href="{{ $bookingTerbaru->previousPageUrl() }}" style="padding:6px 12px; border-radius:8px; border:1.5px solid #e2e8f0; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">‹</a>
        @endif

        @foreach($bookingTerbaru->getUrlRange(1, $bookingTerbaru->lastPage()) as $page => $url)
            @if($page == $bookingTerbaru->currentPage())
                <span style="padding:6px 12px; border-radius:8px; background:#1565C0; color:white; font-size:13px; font-weight:600;">{{ $page }}</span>
            @else
                <a href="{{ $url }}" style="padding:6px 12px; border-radius:8px; border:1.5px solid #e2e8f0; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">{{ $page }}</a>
            @endif
        @endforeach

        @if($bookingTerbaru->hasMorePages())
            <a href="{{ $bookingTerbaru->nextPageUrl() }}" style="padding:6px 12px; border-radius:8px; border:1.5px solid #e2e8f0; color:#475569; font-size:13px; font-weight:600; text-decoration:none;">›</a>
        @else
            <span style="padding:6px 12px; border-radius:8px; border:1.5px solid #e2e8f0; color:#cbd5e1; font-size:13px; font-weight:600;">›</span>
        @endif
    </div>
</div>
</div>

@endsection