@extends('layouts.admin')

@section('title', 'Laporan Rekapitulasi')

@section('content')

<div class="page-header">
    <h1>Laporan Rekapitulasi</h1>
    <p>Rekap data booking dan pendapatan Bintang Sport Center</p>
</div>

{{-- CARD STATISTIK --}}
<div class="row g-3 mb-4">

    {{-- Total Booking --}}
    <div class="col-md-4">
        <div class="card-custom p-4 d-flex align-items-center gap-3">

            <div style="
                width:52px;
                height:52px;
                border-radius:14px;
                background:#e8f0fe;
                display:flex;
                align-items:center;
                justify-content:center;
            ">
                <i class="fas fa-calendar-check"
                   style="color:#1565C0;font-size:22px;"></i>
            </div>

            <div>
                <div style="
                    font-size:12px;
                    font-weight:600;
                    color:#64748b;
                    text-transform:uppercase;
                    letter-spacing:.5px;
                ">
                    Total Booking
                </div>

                <div style="
                    font-size:28px;
                    font-weight:800;
                    color:#1a1a2e;
                ">
                    {{ $totalBooking }}
                </div>
            </div>

        </div>
    </div>

    {{-- Total Pendapatan --}}
    <div class="col-md-4">
        <div class="card-custom p-4 d-flex align-items-center gap-3">

            <div style="
                width:52px;
                height:52px;
                border-radius:14px;
                background:#fff3e0;
                display:flex;
                align-items:center;
                justify-content:center;
            ">
                <i class="fas fa-wallet"
                   style="color:#ef6c00;font-size:22px;"></i>
            </div>

            <div>
                <div style="
                    font-size:12px;
                    font-weight:600;
                    color:#64748b;
                    text-transform:uppercase;
                    letter-spacing:.5px;
                ">
                    Total Pendapatan
                </div>

                <div style="
                    font-size:28px;
                    font-weight:800;
                    color:#1a1a2e;
                ">
                    Rp {{ number_format($totalPendapatan,0,',','.') }}
                </div>
            </div>

        </div>
    </div>

    {{-- Total Pelanggan --}}
    <div class="col-md-4">
        <div class="card-custom p-4 d-flex align-items-center gap-3">

            <div style="
                width:52px;
                height:52px;
                border-radius:14px;
                background:#fce4ec;
                display:flex;
                align-items:center;
                justify-content:center;
            ">
                <i class="fas fa-users"
                   style="color:#c62828;font-size:22px;"></i>
            </div>

            <div>
                <div style="
                    font-size:12px;
                    font-weight:600;
                    color:#64748b;
                    text-transform:uppercase;
                    letter-spacing:.5px;
                ">
                    Total Pelanggan
                </div>

                <div style="
                    font-size:28px;
                    font-weight:800;
                    color:#1a1a2e;
                ">
                    {{ $totalPelanggan }}
                </div>
            </div>

        </div>
    </div>

</div>

{{-- CARD TABEL --}}
<div class="card-custom">
    {{-- FILTER --}}
    <form method="GET" action="{{ route('admin.laporan.index') }}">

<div class="p-4 d-flex justify-content-between align-items-end flex-wrap"
     style="border-bottom:1px solid #f0f4ff;">

    <div class="d-flex gap-3 flex-wrap">

        <div>
            <label class="form-label">Tanggal Awal</label>
            <input type="date"
                   class="form-control"
                   name="tanggal_awal"
                   value="{{ $tanggalAwal }}">
        </div>

        <div>
            <label class="form-label">Tanggal Akhir</label>
            <input type="date"
                   class="form-control"
                   name="tanggal_akhir"
                   value="{{ $tanggalAkhir }}">
        </div>

        <div class="d-flex gap-2 align-self-end">
            <button type="submit" class="btn-primary-custom">
                <i class="fas fa-search"></i>
                Filter
            </button>

            @if(request()->hasAny(['tanggal_awal','tanggal_akhir']))
                <a href="{{ route('admin.laporan.index') }}"
                   class="btn btn-light rounded-3">
                    <i class="fas fa-rotate-left"></i>
                </a>
            @endif
        </div>

    </div>

    <a href="{{ route('admin.laporan.exportPdf',[
        'tanggal_awal'=>$tanggalAwal,
        'tanggal_akhir'=>$tanggalAkhir
    ]) }}"
       class="btn-primary-custom">
        <i class="fas fa-file-pdf"></i>
        Export PDF
    </a>

</div>

</form>



{{-- TABEL --}}
<div class="table-responsive">

<table class="table mb-0" id="laporanTable">

    <thead>

        <tr>

            <th>No</th>

            <th>Kode Booking</th>

            <th>Tanggal</th>

            <th>Lapangan</th>

            <th>Pelanggan</th>

            <th>Total</th>

            <th>Status</th>

        </tr>

    </thead>

    <tbody>
                @forelse($bookings as $booking)
        <tr>

            <td>{{ $loop->iteration }}</td>

            <td>
                <strong>{{ $booking->kode_booking }}</strong>
            </td>

            <td>
                {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}
            </td>

            <td>
                {{ $booking->jadwal->lapangan->nama_lapangan ?? '-' }}
            </td>

            <td>
                {{ $booking->pelanggan->nama_pelanggan ?? '-' }}
            </td>

            <td style="font-weight:700;color:#1565C0;">
                Rp {{ number_format($booking->total_bayar,0,',','.') }}
            </td>

            <td>

                @if($booking->status == 'Berhasil')

                    <span class="badge-aktif">
                        Berhasil
                    </span>

                @elseif($booking->status == 'Tertunda')

                    <span class="badge-proses">
                        Tertunda
                    </span>

                @else

                    <span class="badge-batal">
                        Dibatalkan
                    </span>

                @endif

            </td>

        </tr>

        @empty

        <tr>

           <td colspan="7"
                class="text-center py-4"
                style="color:#94a3b8;">

                <i class="fas fa-file-alt mb-2"
               style="font-size:32px;display:block;margin:0 auto;width:fit-content;"></i>

                Belum ada data laporan

            </td>

        </tr>

        @endforelse

    </tbody>

</table>

</div>

@if($bookings->count())

<div class="d-flex justify-content-between align-items-center px-4 py-3"
     style="border-top:1px solid #f0f4ff;">

    <small class="text-muted">

        Menampilkan
        {{ $bookings->firstItem() }}
        -
        {{ $bookings->lastItem() }}

        dari

        {{ $bookings->total() }}

        data

    </small>

    {{ $bookings->links('pagination::bootstrap-5') }}

</div>

@endif

</div>

@endsection

@push('styles')

<style>

    nav[aria-label="Pagination Navigation"]>div:first-child{
        display:none!important;
    }

    .badge-aktif{
        background:#dcfce7;
        color:#15803d;
        padding:6px 14px;
        border-radius:30px;
        font-size:12px;
        font-weight:600;
    }

    .badge-proses{
        background:#fff7d6;
        color:#ca8a04;
        padding:6px 14px;
        border-radius:30px;
        font-size:12px;
        font-weight:600;
    }

    .badge-batal{
        background:#fee2e2;
        color:#dc2626;
        padding:6px 14px;
        border-radius:30px;
        font-size:12px;
        font-weight:600;
    }

    #laporanTable thead tr th:first-child{
        border-top-left-radius:12px;
    }

    #laporanTable thead tr th:last-child{
        border-top-right-radius:12px;
    }

    #laporanTable thead{
        overflow:hidden;
    }

    .table-responsive{
        border-radius:12px 12px 0 0;
        overflow:hidden;
    }

</style>

@endpush