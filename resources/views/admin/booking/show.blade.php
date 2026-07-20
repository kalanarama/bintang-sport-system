@extends('layouts.admin')

@section('title', 'Detail Booking')

@section('content')

<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Detail Booking</h1>
        <p>Informasi lengkap data pemesanan lapangan</p>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-light px-4 rounded-3" style="margin-top:20px;">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">

    {{-- INFO BOOKING --}}
    <div class="col-md-6">
        <div class="card-custom p-4">
            <div style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">
                <i class="fas fa-ticket" style="color:#1565C0;margin-right:6px;"></i> Info Booking
            </div>

            <div class="detail-row">
                <span class="detail-label">Kode Booking</span>
                <span class="detail-value" style="color:#1565C0;font-weight:700;">{{ $booking->kode_booking }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Booking</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($booking->created_at)->isoFormat('D MMMM YYYY, HH:mm') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status Booking</span>
                <span class="detail-value">
                    @if($booking->status == 'Berhasil')
                        <span class="badge-aktif">Berhasil</span>
                    @elseif($booking->status == 'Tertunda')
                        <span class="badge-proses">Tertunda</span>
                    @else
                        <span class="badge-batal">Dibatalkan</span>
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total Bayar</span>
                <span class="detail-value" style="color:#1565C0;font-weight:700;font-size:16px;">
                    Rp {{ number_format($booking->total_bayar, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- INFO JADWAL --}}
    <div class="col-md-6">
        <div class="card-custom p-4">
            <div style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">
                <i class="fas fa-calendar-days" style="color:#1565C0;margin-right:6px;"></i> Info Jadwal
            </div>

            <div class="detail-row">
                <span class="detail-label">Lapangan</span>
                <span class="detail-value">{{ optional(optional($booking->jadwal)->lapangan)->nama_lapangan ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Tanggal Main</span>
                <span class="detail-value">
                    {{ $booking->jadwal ? \Carbon\Carbon::parse($booking->jadwal->tanggal_jadwal)->isoFormat('D MMMM YYYY') : '-' }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Jam Main</span>
                <span class="detail-value">
                    {{ $booking->jadwal ? substr($booking->jadwal->jam_mulai, 0, 5).' - '.substr($booking->jadwal->jam_selesai, 0, 5) : '-' }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status Jadwal</span>
                <span class="detail-value">
                    @if(optional($booking->jadwal)->status_jadwal == 'Penuh')
                        <span class="badge-batal">Penuh</span>
                    @else
                        <span class="badge-aktif">Tersedia</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- INFO PELANGGAN --}}
    <div class="col-md-6">
        <div class="card-custom p-4">
            <div style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">
                <i class="fas fa-user" style="color:#1565C0;margin-right:6px;"></i> Info Pelanggan
            </div>

            <div class="detail-row">
                <span class="detail-label">Nama</span>
                <span class="detail-value">{{ optional($booking->pelanggan)->nama_pelanggan ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">No WhatsApp</span>
                <span class="detail-value">{{ optional($booking->pelanggan)->nomor_hp ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- INFO PEMBAYARAN --}}
    <div class="col-md-6">
        <div class="card-custom p-4">
            <div style="font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">
                <i class="fas fa-wallet" style="color:#1565C0;margin-right:6px;"></i> Info Pembayaran
            </div>

            @if($booking->pembayaran)
                <div class="detail-row">
                    <span class="detail-label">Metode</span>
                    <span class="detail-value">{{ $booking->pembayaran->metode_pembayaran ?? '-' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status Bayar</span>
                    <span class="detail-value">
                        @if($booking->pembayaran->status_pembayaran == 'Berhasil')
                            <span class="badge-aktif">Berhasil</span>
                        @elseif($booking->pembayaran->status_pembayaran == 'Tertunda')
                            <span class="badge-proses">Tertunda</span>
                        @else
                            <span class="badge-batal">Gagal</span>
                        @endif
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Waktu Bayar</span>
                    <span class="detail-value">
                        {{ $booking->pembayaran->created_at ? \Carbon\Carbon::parse($booking->pembayaran->created_at)->isoFormat('D MMMM YYYY, HH:mm') : '-' }}
                    </span>
                </div>
            @else
                <div class="text-center py-3" style="color:#94a3b8;font-size:13px;">
                    <div><i class="fas fa-clock mb-2" style="font-size:28px;"></i></div>
                    Belum ada data pembayaran
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f4ff;
        font-size: 13px;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: #64748b; }
    .detail-value { font-weight: 600; color: #1a1a2e; text-align: right; }

    .badge-aktif {
        background: #dcfce7; color: #15803d;
        padding: 4px 12px; border-radius: 30px;
        font-size: 12px; font-weight: 600;
    }
    .badge-proses {
        background: #fff7d6; color: #ca8a04;
        padding: 4px 12px; border-radius: 30px;
        font-size: 12px; font-weight: 600;
    }
    .badge-batal {
        background: #fee2e2; color: #dc2626;
        padding: 4px 12px; border-radius: 30px;
        font-size: 12px; font-weight: 600;
    }
</style>
@endpush