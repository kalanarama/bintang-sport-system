@extends('layouts.admin')

@section('title', 'Kelola Promo')

@section('content')
<div class="page-header">
    <h1>Kelola Promo</h1>
    <p>Manajemen data promo dan diskon lapangan</p>
</div>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#1565C0',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
        });
    });
</script>
@endif

{{-- CARD STATISTIK --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card-custom p-4 d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:14px;background:#e8f0fe;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-percentage" style="color:#1565C0;font-size:22px;"></i>
            </div>
            <div>
                <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Promo Aktif</div>
                <div style="font-size:28px;font-weight:800;color:#1a1a2e;">{{ $totalAktif ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom p-4 d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:14px;background:#fff3e0;display:flex;align-items:center;justify-content:center;">
                <i class="far fa-clock" style="color:#e65100;font-size:22px;"></i>
            </div>
            <div>
                <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Berakhir Minggu Ini</div>
                <div style="font-size:28px;font-weight:800;color:#1a1a2e;">{{ $berakhirMingguIni ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom p-4 d-flex align-items-center gap-3">
            <div style="width:52px;height:52px;border-radius:14px;background:#fce4ec;display:flex;align-items:center;justify-content:center;">
                <i class="far fa-times-circle" style="color:#c62828;font-size:22px;"></i>
            </div>
            <div>
                <div style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.5px;">Promo Nonaktif</div>
                <div style="font-size:28px;font-weight:800;color:#1a1a2e;">{{ $totalNonaktif ?? 0 }}</div>
            </div>
        </div>
    </div>
</div>

{{-- TABEL --}}
<div class="card-custom">

    {{-- SEARCH & FILTER + TAMBAH --}}
    <form method="GET" action="{{ route('admin.promo.index') }}">
        <div class="p-4 d-flex gap-3 flex-wrap align-items-center" style="border-bottom:1px solid #f0f4ff;">
            <div style="flex:1;min-width:200px;">
                <div style="position:relative;">
                    <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;"></i>
                    <input type="text" name="search" id="searchInput" class="form-control"
                        placeholder="Cari nama promo..."
                        style="padding-left:36px;"
                        value="{{ request('search') }}">
                </div>
            </div>
            <div>
                <select name="status" id="filterStatus" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status') == 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div>
                <select name="lapangan" id="filterLapangan" class="form-select">
                    <option value="">Semua Lapangan</option>
                    @foreach($lapangans ?? [] as $lap)
                    <option value="{{ $lap->id }}" {{ request('lapangan') == $lap->id ? 'selected' : '' }}>
                        {{ $lap->nama_lapangan }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn-primary-custom">
                    <i class="fas fa-search"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'lapangan']))
                <a href="{{ route('admin.promo.index') }}" class="btn btn-light px-3 rounded-3" title="Reset">
                    <i class="fas fa-rotate-left"></i>
                </a>
                @endif
                <a href="{{ route('admin.promo.create') }}" class="btn-primary-custom">
                    <i class="fas fa-plus"></i> Tambah Promo
                </a>
            </div>
        </div>
    </form>

    {{-- TABEL DATA --}}
    <div class="table-responsive">
        <table class="table mb-0" id="promoTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Promo</th>
                    <th>Lapangan</th>
                    <th>Diskon</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Berakhir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="promoBody">
                @forelse($promos ?? [] as $index => $promo)
                <tr>
                    <td>{{ $promos->firstItem() + $loop->index }}</td>
                    <td style="font-weight:600;">{{ $promo->nama_promo }}</td>
                    <td>
                        @foreach($promo->lapangans as $lap)
                            <span class="badge-lapangan">{{ $lap->nama_lapangan }}</span>
                        @endforeach
                    </td>
                    <td>
                        <span style="font-weight:700;color:#1565C0;">{{ $promo->diskon_persen }}%</span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($promo->tanggal_mulai)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($promo->tanggal_berakhir)->format('d M Y') }}</td>
                    <td>
                        @if($promo->isAktif())
                            <span class="badge-aktif">Aktif</span>
                        @else
                            <span class="badge-nonaktif">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.promo.show', $promo->id) }}" class="btn-detail" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.promo.edit', $promo->id) }}" class="btn-edit" title="Edit">
                                <i class="fas fa-pen-to-square"></i>
                            </a>
                            <button type="button" class="btn-hapus" title="Hapus"
                                onclick="confirmDelete({{ $promo->id }}, '{{ addslashes($promo->nama_promo) }}')">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4" style="color:#94a3b8;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
                            <i class="fas fa-tag" style="font-size:32px;"></i>
                            <span>Tidak ada promo yang sesuai.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($promos) && $promos->count() > 0)
    <div class="d-flex justify-content-between align-items-center px-4 py-3" style="border-top:1px solid #f0f4ff;">
        <small class="text-muted">
            Menampilkan {{ $promos->firstItem() }}-{{ $promos->lastItem() }} dari {{ $promos->total() }} data
        </small>
        {{ $promos->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>

<form id="formHapus" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('styles')
<style>
    nav[aria-label="Pagination Navigation"] > div:first-child { display: none !important; }

    .badge-lapangan {
        display: inline-block;
        background: #e8f0fe;
        color: #1565C0;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin: 2px 2px 2px 0;
    }
    .btn-detail, .btn-edit, .btn-hapus {
        border: none;
        border-radius: 8px;
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
    }
    .btn-detail { background:#fff3e0; color:#e65100; }
    .btn-detail:hover { background:#e65100; color:#fff; }
    .btn-edit { background:#e3f2fd; color:#1976d2; }
    .btn-edit:hover { background:#1976d2; color:#fff; }
    .btn-hapus { background:#fce4ec; color:#c62828; }
    .btn-hapus:hover { background:#c62828; color:#fff; }

    #promoTable thead tr th:first-child {
    border-top-left-radius: 12px;
    }
    #promoTable thead tr th:last-child {
        border-top-right-radius: 12px;
    }
    #promoTable thead {
        overflow: hidden;
    }
    .table-responsive {
        border-radius: 12px 12px 0 0;
        overflow: hidden;
    }
    #promoTable thead th {
        white-space: nowrap;
        vertical-align: middle;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Hapus Promo?',
            text: `Promo "${nama}" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c62828',
            cancelButtonColor: '#e2e8f0',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: { cancelButton: 'text-dark', popup: 'rounded-4' }
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.getElementById('formHapus');
                form.action = `/admin/promo/${id}`;
                form.submit();
            }
        });
    }
</script>
@endpush