@extends('layouts.admin')

@section('title', 'Kelola Jenis Lapangan')

@section('content')
<div class="page-header d-flex align-items-start justify-content-between">
    <div>
        <h1>Kelola Jenis Lapangan</h1>
        <p>Kelola data jenis lapangan yang tersedia di Bintang Sport Center.</p>
    </div>
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
                    <th>Jenis Lapangan</th>
                    <th>Harga/Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
    @forelse($jenisLapangans as $index => $jenis)
    <tr>
        <td>{{ $jenisLapangans->firstItem() + $index }}</td>
        <td>{{ $jenis->nama_jenis_lapangan }}</td>
        <td>Rp{{ number_format($jenis->harga_per_jam,0,',','.') }}</td>
        <td>
            <a href="{{ route('admin.jenisLapangan.edit', $jenis->id) }}" class="btn-edit">
                <i class="fas fa-pen-to-square"></i>
            </a>
        </td>
    </tr>

    @empty
    <tr>
        <td colspan="4" class="text-center text-muted py-4">
            Belum ada data jenis lapangan.
        </td>
    </tr>
    @endforelse
</tbody>
        </table>
    </div>
    @if($jenisLapangans->total() > 0)
    <div class="d-flex justify-content-between align-items-center px-3 py-3"
        style="border-top: 1px solid #f0f4ff;">
        <small class="text-muted">
            Menampilkan {{ $jenisLapangans->firstItem() }}-{{ $jenisLapangans->lastItem() }}
            dari {{ $jenisLapangans->total() }} data
        </small>
        {{ $jenisLapangans->links('pagination::bootstrap-5') }}
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

</script>
@endpush