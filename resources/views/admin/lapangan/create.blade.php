@extends('layouts.admin')

@section('title', 'Tambah Lapangan')

@section('content')
<div class="page-header">
    <h1>Tambah Lapangan</h1>
    <p>Isi data lapangan baru yang akan ditambahkan.</p>
</div>

<div class="card-custom" style="max-width: 600px; padding: 28px;">
    <form action="{{ route('admin.lapangan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Lapangan</label>
            <input type="text" name="nama_lapangan"
                class="form-control @error('nama_lapangan') is-invalid @enderror"
                placeholder="Contoh: Badminton A"
                value="{{ old('nama_lapangan') }}">
            @error('nama_lapangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Lapangan</label>
            <select name="jenis_lapangan" class="form-select @error('jenis_lapangan') is-invalid @enderror">
                <option value="">Pilih Jenis</option>
                <option value="Badminton" {{ old('jenis_lapangan') == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                <option value="Futsal" {{ old('jenis_lapangan') == 'Futsal' ? 'selected' : '' }}>Futsal</option>
                <option value="Basket" {{ old('jenis_lapangan') == 'Basket' ? 'selected' : '' }}>Basket</option>
            </select>
            @error('jenis_lapangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Harga/Jam</label>
            <div class="input-group">
                <span class="input-group-text" style="background:#f0f4ff; border-color:#e0e7ff;">Rp</span>
                <input type="number" name="harga_lapangan"
                    class="form-control @error('harga_lapangan') is-invalid @enderror"
                    placeholder="50000"
                    min="1"
                    value="{{ old('harga_lapangan') }}">
            </div>
            @error('harga_lapangan')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Lapangan</label>
            <input type="file" name="foto_lapangan" accept="image/*"
                class="form-control @error('foto_lapangan') is-invalid @enderror"
                onchange="previewFoto(this)">
            <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 5MB.</small>
            @error('foto_lapangan')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <div id="preview" class="mt-2" style="display:none;">
                <img id="previewImg" src="" alt="Preview"
                    style="width:100%; max-height:200px; object-fit:cover; border-radius:10px;">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Status</label>
            <select name="status_lapangan" class="form-select @error('status_lapangan') is-invalid @enderror">
                <option value="aktif" {{ old('status_lapangan') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status_lapangan') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @error('status_lapangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn-primary-custom">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('admin.lapangan.index') }}" class="btn btn-light px-4 rounded-3">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewFoto(input) {
        const preview = document.getElementById('preview');
        const previewImg = document.getElementById('previewImg');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush