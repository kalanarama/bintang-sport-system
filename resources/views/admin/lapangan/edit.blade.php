@extends('layouts.admin')

@section('title', 'Edit Lapangan')

@section('content')
<div class="page-header">
    <h1>Edit Lapangan</h1>
    <p>Ubah data lapangan yang sudah ada.</p>
</div>

<div class="card-custom" style="max-width: 600px; padding: 28px;">
    <form action="{{ route('admin.lapangan.update', $lapangan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Lapangan</label>
            <input type="text" name="nama_lapangan"
                class="form-control @error('nama_lapangan') is-invalid @enderror"
                value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}">
            @error('nama_lapangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Jenis Lapangan</label>
            <select name="jenis_lapangan_id" id="jenisLapanganSelect" class="form-select @error('jenis_lapangan_id') is-invalid @enderror">
                <option value="">Pilih Jenis</option>
                @foreach($jenisLapangans as $jenis)
                    <option value="{{ $jenis->id }}" {{ (int)old('jenis_lapangan_id', $lapangan->jenis_lapangan_id) === (int)$jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama_jenis_lapangan }}
                    </option>
                @endforeach
            </select>
            @error('jenis_lapangan_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Harga/Jam</label>
            <div class="input-group">
                <span class="input-group-text" style="background:#f0f4ff; border-color:#e0e7ff;">Rp</span>
                <input type="number" name="harga_per_jam" id="inputHarga"
                    class="form-control @error('harga_per_jam') is-invalid @enderror"
                    value="{{ old('harga_per_jam', $lapangan->jenisLapangan->harga_per_jam ?? '') }}">
            </div>
            <small class="text-warning mt-1 d-block">
                <i class="fas fa-triangle-exclamation"></i>
                Mengubah harga akan mempengaruhi semua lapangan dengan jenis
                <strong>{{ $lapangan->jenisLapangan->nama_jenis_lapangan ?? '-' }}</strong>.
            </small>
            @error('harga_per_jam')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Jam Operasional</label>
            <div class="d-flex align-items-start gap-2">
                <div class="flex-fill">
                    <input type="time" name="jam_buka"
                        class="form-control @error('jam_buka') is-invalid @enderror"
                        value="{{ old('jam_buka', $lapangan->jam_buka) }}">
                    @error('jam_buka')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <span class="text-muted mt-2">s/d</span>
                <div class="flex-fill">
                    <input type="time" name="jam_tutup"
                        class="form-control @error('jam_tutup') is-invalid @enderror"
                        value="{{ old('jam_tutup', $lapangan->jam_tutup) }}">
                    @error('jam_tutup')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Durasi Slot</label>
            <select name="durasi_slot" class="form-select @error('durasi_slot') is-invalid @enderror">
                <option value="">Pilih Durasi</option>
                <option value="60" {{ old('durasi_slot', $lapangan->durasi_slot) == '60' ? 'selected' : '' }}>60 Menit</option>
                <option value="120" {{ old('durasi_slot', $lapangan->durasi_slot) == '120' ? 'selected' : '' }}>120 Menit</option>
            </select>
            @error('durasi_slot')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Lapangan</label>
            @if($lapangan->foto_lapangan)
                <div class="mb-2">
                    <img src="{{ asset($lapangan->foto_lapangan) }}" alt="Foto Lapangan"
                        style="width:100%; max-height:200px; object-fit:cover; border-radius:10px;">
                    <small class="text-muted">Foto saat ini. Upload baru untuk mengganti.</small>
                </div>
            @endif
            <input type="file" name="foto_lapangan" accept="image/*"
                class="form-control @error('foto_lapangan') is-invalid @enderror"
                onchange="validateAndPreview(this)">>
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
                <option value="aktif" {{ old('status_lapangan', $lapangan->status_lapangan) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status_lapangan', $lapangan->status_lapangan) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @error('status_lapangan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn-primary-custom">
                <i class="fas fa-save"></i> Simpan Perubahan
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

    function validateAndPreview(input) {
    const maxSize = 5 * 1024 * 1024;
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    const file = input.files[0];
    if (!file) return;

    let errDiv = document.getElementById('fotoError');
    if (!errDiv) {
        errDiv = document.createElement('div');
        errDiv.id = 'fotoError';
        errDiv.className = 'invalid-feedback d-block';
        input.parentNode.appendChild(errDiv);
    }

    if (!allowedTypes.includes(file.type)) {
        input.value = '';
        document.getElementById('preview').style.display = 'none';
        errDiv.textContent = 'Format foto tidak valid. Hanya JPG, JPEG, dan PNG yang diperbolehkan.';
        return;
    }

    if (file.size > maxSize) {
        input.value = '';
        document.getElementById('preview').style.display = 'none';
        errDiv.textContent = 'Ukuran foto terlalu besar. Maksimal 5MB, ukuran kamu: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB.';
        return;
    }

    errDiv.textContent = '';

    const preview = document.getElementById('preview');
    const previewImg = document.getElementById('previewImg');
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src = e.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
document.querySelector('form').addEventListener('submit', function(e) {
    const harga = document.querySelector('[name="harga_per_jam"]');
    document.getElementById('error-harga')?.remove();

    if (!harga.value || parseInt(harga.value) < 30000) {
        harga.classList.add('is-invalid');
        const el = document.createElement('div');
        el.id = 'error-harga';
        el.className = 'invalid-feedback d-block';
        el.textContent = !harga.value
            ? 'Harga per jam wajib diisi.'
            : 'Harga per jam minimal Rp 30.000.';
        harga.closest('.input-group').insertAdjacentElement('afterend', el);
        e.preventDefault();
    } else {
        harga.classList.remove('is-invalid');
    }
});

document.querySelector('[name="harga_per_jam"]').addEventListener('input', function() {
    if (this.value && parseInt(this.value) >= 30000) {
        this.classList.remove('is-invalid');
        document.getElementById('error-harga')?.remove();
    }
});
</script>
@endpush