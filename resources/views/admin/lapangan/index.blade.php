@extends('layouts.admin')

@section('title', 'Kelola Lapangan')

@section('content')
<div class="page-header d-flex align-items-start justify-content-between">
    <div>
        <h1>Kelola Lapangan</h1>
        <p>Kelola data lapangan yang tersedia di Bintang Sport Center.</p>
    </div>
    <a href="{{ route('admin.lapangan.create') }}" class="btn-primary-custom" style="margin-top: 20px;">
        <i class="fas fa-plus"></i> Tambah Lapangan
    </a>
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#1565C0',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false,
            });
        });
    </script>
@endif

<div class="card-custom">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Lapangan</th>
                    <th>Jenis</th>
                    <th>Harga/Jam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lapangans as $index => $lapangan)
                <tr>
                    <td>{{ $lapangans->firstItem() + $index }}</td>
                    <td>
                        @if($lapangan->foto_lapangan)
                           <img src="{{ asset($lapangan->foto_lapangan) }}"
                                 alt="Foto" style="width:80px; height:60px; object-fit:cover; border-radius:8px;">
                        @else
                            <div style="width:60px; height:50px; background:#f0f4ff; border-radius:8px;
                                display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $lapangan->nama_lapangan }}</td>
                    <td>{{ $lapangan->jenisLapangan->nama_jenis_lapangan ?? '-' }}</td>

                    <td>
                        Rp{{ number_format($lapangan->jenisLapangan->harga_per_jam ?? 0, 0, ',', '.') }}
                    </td>
                    <td>
                        @if($lapangan->status_lapangan == 'aktif')
                            <span class="badge-aktif">Aktif</span>
                        @else
                            <span class="badge-nonaktif">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.lapangan.show', $lapangan->id) }}" class="btn-detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.lapangan.edit', $lapangan->id) }}" class="btn-edit">
                                <i class="fas fa-pen-to-square"></i>
                            </a>
                            <button type="button" class="btn-hapus"
                                onclick="konfirmasiHapus('{{ route('admin.lapangan.destroy', $lapangan->id) }}')">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Belum ada data lapangan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($lapangans->total() > 0)
    <div class="d-flex justify-content-between align-items-center px-3 py-3"
        style="border-top: 1px solid #f0f4ff;">
        <small class="text-muted">
            Menampilkan {{ $lapangans->firstItem() }}-{{ $lapangans->lastItem() }}
            dari {{ $lapangans->total() }} data
        </small>
        {{ $lapangans->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<form id="formHapus" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    function konfirmasiHapus(url) {
        Swal.fire({
            title: 'Hapus Lapangan?',
            text: 'Data lapangan yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#c62828',
            cancelButtonColor: '#e2e8f0',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                cancelButton: 'text-dark',
                popup: 'rounded-4',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('formHapus');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
@endpush