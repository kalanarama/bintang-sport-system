@extends('layouts.admin')

@section('title', 'Detail Lapangan')

@push('styles')
<style>
    .table-detail td {
        border: none !important;
        padding: 12px 16px;
        border-bottom: 1px solid #f0f4ff !important;
    }
    .table-detail tr:last-child td {
        border-bottom: none !important;
    }
</style>
@endpush

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Detail Lapangan</h1>
        <p>Informasi lengkap data lapangan.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.lapangan.edit', $lapangan->id) }}" class="btn-primary-custom">
            <i class="fas fa-pen-to-square"></i> Edit
        </a>
        <a href="{{ route('admin.lapangan.index') }}" class="btn btn-light px-4 rounded-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row gap-4">
    <!-- Foto -->
    <div class="col-md-4">
        <div class="card-custom p-3">
            @if($lapangan->foto_lapangan)
                <img src="{{ asset($lapangan->foto_lapangan) }}" alt="Foto Lapangan"
                    style="width:100%; height:250px; object-fit:cover; border-radius:12px;">
            @else
                <div style="width:100%; height:250px; background:#f0f4ff; border-radius:12px;
                    display:flex; align-items:center; justify-content:center; color:#94a3b8;
                    flex-direction:column; gap:8px;">
                    <i class="fas fa-image" style="font-size:48px;"></i>
                    <small>Belum ada foto</small>
                </div>
            @endif
        </div>
    </div>

    <!-- Detail -->
    <div class="col-md-7">
        <div class="card-custom" style="padding: 28px;">
            <table class="table table-detail mb-0">
                <tr>
                    <td style="width:160px; color:#64748b; font-weight:600;">Nama Lapangan</td>
                    <td style="color:#64748b;">:</td>
                    <td style="font-weight:600; color:#1a1a2e;">{{ $lapangan->nama_lapangan }}</td>
                </tr>
                <tr>
                    <td style="color:#64748b; font-weight:600;">Jenis Lapangan</td>
                    <td style="color:#64748b;">:</td>
                    <td style="font-weight:600; color:#1a1a2e;">{{ $lapangan->jenis_lapangan }}</td>
                </tr>
                <tr>
                    <td style="color:#64748b; font-weight:600;">Harga/Jam</td>
                    <td style="color:#64748b;">:</td>
                    <td style="font-weight:600; color:#1a1a2e;">Rp{{ number_format($lapangan->harga_lapangan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td style="color:#64748b; font-weight:600;">Jam Operasional</td>
                    <td style="color:#64748b;">:</td>
                    <td style="font-weight:600; color:#1a1a2e;">
                        {{ $lapangan->jam_buka }} – {{ $lapangan->jam_tutup }}
                    </td>
                </tr>
                <tr>
                    <td style="color:#64748b; font-weight:600;">Durasi Slot</td>
                    <td style="color:#64748b;">:</td>
                    <td style="font-weight:600; color:#1a1a2e;">{{ $lapangan->durasi_slot }} Menit</td>
                </tr>
                <tr>
                    <td style="color:#64748b; font-weight:600;">Status</td>
                    <td style="color:#64748b;">:</td>
                    <td>
                        @if($lapangan->status_lapangan == 'aktif')
                            <span class="badge-aktif">Aktif</span>
                        @else
                            <span class="badge-nonaktif">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="color:#64748b; font-weight:600;">Ditambahkan</td>
                    <td style="color:#64748b;">:</td>
                    <td style="color:#1a1a2e;">{{ $lapangan->created_at ? $lapangan->created_at->format('d M Y, H:i') : '-' }}</td>
                </tr>
                <tr>
                    <td style="color:#64748b; font-weight:600;">Diperbarui</td>
                    <td style="color:#64748b;">:</td>
                    <td style="color:#1a1a2e;">{{ $lapangan->updated_at ? $lapangan->updated_at->format('d M Y, H:i') : '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection