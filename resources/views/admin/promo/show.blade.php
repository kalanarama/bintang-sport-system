@extends('layouts.admin')

@section('title', 'Detail Promo')

@push('styles')
<style>
    .detail-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px 12px;
        text-align: center;
    }
    .detail-card-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .7px;
        margin-bottom: 10px;
    }
    .detail-lap-block {
        background: #fafbff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 14px;
    }
    .detail-lap-nama {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a2e;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    .detail-lap-harga {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 10px;
        padding-left: 22px;
    }
    .detail-lap-slots {
        padding-left: 22px;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .slot-badge {
        display: inline-block;
        background: #e8f0fe;
        color: #1565C0;
        border: 1px solid #c5d8fb;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1>Detail Promo</h1>
            <p>Informasi lengkap data promo.</p>
        </div>
        <div class="d-flex gap-2" style="margin-top: 10px; margin-bottom: 10px;">
        <a href="{{ route('admin.promo.edit', $promo->id) }}" class="btn-primary-custom">
            <i class="fas fa-pen-to-square"></i> Edit
        </a>
        <a href="{{ route('admin.promo.index') }}" class="btn btn-light px-4 rounded-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- INFO CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="detail-card">
            <div class="detail-card-label">
                <i class="fas fa-percentage me-1" style="color:#1565C0;"></i> Diskon
            </div>
            <div style="font-size:36px;font-weight:800;color:#1565C0;line-height:1.2;">
                {{ $promo->diskon_persen }}%
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="detail-card">
            <div class="detail-card-label">
                <i class="fas fa-calendar-alt me-1" style="color:#2e7d32;"></i> Tanggal Mulai
            </div>
            <div style="font-size:15px;font-weight:700;color:#1a1a2e;">
                {{ $promo->tanggal_mulai->locale('id')->isoFormat('DD MMMM YYYY') }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="detail-card">
            <div class="detail-card-label">
                <i class="fas fa-calendar-check me-1" style="color:#c62828;"></i> Tanggal Berakhir
            </div>
            <div style="font-size:15px;font-weight:700;color:#1a1a2e;">
                {{ $promo->tanggal_berakhir->locale('id')->isoFormat('DD MMMM YYYY') }}
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="detail-card">
            <div class="detail-card-label">
                <i class="fas fa-circle-check me-1"></i> Status
            </div>
            @if($promo->isAktif())
                <span class="badge-aktif" style="font-size:13px;padding:6px 16px;">Aktif</span>
            @else
                <span class="badge-nonaktif" style="font-size:13px;padding:6px 16px;">Nonaktif</span>
            @endif
        </div>
    </div>
</div>

{{-- LAPANGAN & SLOT --}}
<div class="card-custom" style="padding: 28px;">
    <h5 style="font-weight:700;color:#1a1a2e;margin-bottom:20px;">Lapangan & Slot Jam</h5>

    @forelse($promo->lapangans as $lapangan)
    @php
        $hargaAsal    = $lapangan->harga_lapangan;
        $hargaSetelah = $hargaAsal - ($hargaAsal * $promo->diskon_persen / 100);
        $rawSlots     = $lapangan->pivot->slots ?? [];
        $slots        = is_array($rawSlots)
                            ? $rawSlots
                            : (json_decode($rawSlots, true) ?? []);
    @endphp
    <div class="detail-lap-block">
        <div class="detail-lap-nama">
            <i class="fas fa-flag" style="color:#1565C0;font-size:13px;"></i>
            {{ $lapangan->nama_lapangan }}
        </div>
        <div class="detail-lap-harga">
            Harga:
            <span style="text-decoration:line-through;color:#94a3b8;">
                Rp{{ number_format($hargaAsal, 0, ',', '.') }}
            </span>
            <i class="fas fa-arrow-right mx-1" style="font-size:10px;color:#94a3b8;"></i>
            <span style="color:#1565C0;font-weight:700;">
                Rp{{ number_format($hargaSetelah, 0, ',', '.') }}
            </span>
            <span style="color:#94a3b8;font-size:11px;">({{ $promo->diskon_persen }}% off)</span>
        </div>
        <div class="detail-lap-slots">
            @if(count($slots) > 0)
                @foreach($slots as $slot)
                    <span class="slot-badge">
                        <i class="fas fa-clock me-1" style="font-size:10px;"></i>{{ $slot }}
                    </span>
                @endforeach
            @else
                <span style="font-size:13px;color:#94a3b8;font-style:italic;">
                    Belum ada slot yang dipilih
                </span>
            @endif
        </div>
    </div>
    @empty
    <p class="text-muted text-center py-4">Tidak ada lapangan yang terdaftar.</p>
    @endforelse
</div>
@endsection