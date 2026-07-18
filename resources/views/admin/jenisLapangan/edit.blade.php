@extends('layouts.admin')

@section('title', 'Edit Jenis Lapangan')

@section('content')
<div class="page-header">
    <h1>Edit Jenis Lapangan</h1>
    <p>Ubah data jenis lapangan yang sudah ada.</p>
</div>

<div class="card-custom" style="max-width: 600px; padding: 28px;">
    <form action="{{ route('admin.jenisLapangan.update', $jenisLapangan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Jenis Lapangan</label>
            <input
                type="text"
                name="nama_jenis_lapangan"
                class="form-control @error('nama_jenis_lapangan') is-invalid @enderror"
                value="{{ old('nama_jenis_lapangan', $jenisLapangan->nama_jenis_lapangan) }}"
                placeholder="Contoh: Futsal A">

            @error('nama_jenis_lapangan')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Harga per Jam</label>

            <div class="input-group">
                <span class="input-group-text"
                    style="background:#f0f4ff;border-color:#e0e7ff;">
                    Rp
                </span>

                <input
                    type="number"
                    name="harga_per_jam"
                    min="0"
                    class="form-control @error('harga_per_jam') is-invalid @enderror"
                    value="{{ old('harga_per_jam', $jenisLapangan->harga_per_jam) }}"
                    placeholder="70000">
            </div>

            @error('harga_per_jam')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn-primary-custom">
                <i class="fas fa-save"></i>
                Simpan Perubahan
            </button>

            <a href="{{ route('admin.jenisLapangan.index') }}"
               class="btn btn-light px-4 rounded-3">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection