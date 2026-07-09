@extends('layouts.admin')

@section('title', 'Tambah Promo')

@section('content')
<div class="page-header d-flex justify-content-between align-items-start">
    <div>
        <h1>Tambah Promo</h1>
        <p>Tambah data promo baru untuk lapangan.</p>
    </div>
    <a href="{{ route('admin.promo.index') }}" class="btn btn-light px-4 rounded-3">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card-custom" style="padding: 28px;">
    <form action="{{ route('admin.promo.store') }}" method="POST" id="formTambah">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Promo <span class="text-danger">*</span></label>
            <input type="text" name="nama_promo" class="form-control @error('nama_promo') is-invalid @enderror"
                placeholder="Masukkan nama promo..."
                value="{{ old('nama_promo') }}">
            @error('nama_promo')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Besaran Diskon (%) <span class="text-danger">*</span></label>
            <input type="number" name="diskon_persen" id="diskon_input"
                class="form-control @error('diskon_persen') is-invalid @enderror"
                placeholder="Contoh: 20" min="1" max="100"
                value="{{ old('diskon_persen') }}">
            @error('diskon_persen')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-3">
            <div class="col-6">
                <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_mulai"
                    class="form-control @error('tanggal_mulai') is-invalid @enderror"
                    value="{{ old('tanggal_mulai') }}">
                @error('tanggal_mulai')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-6">
                <label class="form-label fw-semibold">Tanggal Berakhir <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_berakhir"
                    class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                    value="{{ old('tanggal_berakhir') }}">
                @error('tanggal_berakhir')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Pilih Lapangan <span class="text-danger">*</span></label>
            @error('lapangan_ids')
                <div class="text-danger small mb-1">{{ $message }}</div>
            @enderror
            <div class="lapangan-checkbox-grid">
                @foreach($lapangans as $lapangan)
                <div class="form-check">
                    <input class="form-check-input lap-cb" type="checkbox"
                        name="lapangan_ids[]" value="{{ $lapangan->id }}"
                        id="lap_{{ $lapangan->id }}"
                        data-nama="{{ $lapangan->nama_lapangan }}"
                        data-harga="{{ $lapangan->harga_lapangan }}"
                        data-jam-buka="{{ $lapangan->jam_buka }}"
                        data-jam-tutup="{{ $lapangan->jam_tutup }}"
                        data-durasi="{{ $lapangan->durasi_slot }}"
                        onchange="toggleSlot(this)"
                        {{ in_array($lapangan->id, old('lapangan_ids', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="lap_{{ $lapangan->id }}">
                        {{ $lapangan->nama_lapangan }}
                        <span style="color:#94a3b8;font-size:11px;margin-left:6px;">|</span>
                        <span style="color:#1565C0;font-size:12px;font-weight:700;">
                            Rp{{ number_format($lapangan->harga_lapangan, 0, ',', '.') }}
                        </span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <div id="slot_container"></div>

        <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn-primary-custom">
                <i class="fas fa-save"></i> Simpan Promo
            </button>
            <a href="{{ route('admin.promo.index') }}" class="btn btn-light px-4 rounded-3">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .lapangan-checkbox-grid {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 12px 16px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 6px 16px;
    }
    .slot-section {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        background: #fafbff;
    }
    .slot-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .slot-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a1a2e;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .harga-coret { text-decoration: line-through; color: #94a3b8; font-size: 12px; font-weight: 400; }
    .harga-diskon { color: #1565C0; font-weight: 700; font-size: 12px; }
    .slot-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 6px;
    }
    .slot-grid .form-check {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 10px 6px 32px;
        font-size: 12px;
        transition: all .15s;
        cursor: pointer;
    }
    .slot-grid .form-check:hover { border-color: #1565C0; background: #e8f0fe; }
    .slot-grid .form-check-input:checked { background-color: #1565C0; border-color: #1565C0; }
    .slot-grid .form-check-input:checked + label { color: #1565C0; font-weight: 600; }
    .btn-pilih-semua {
        background: none;
        border: 1px solid #1565C0;
        color: #1565C0;
        border-radius: 6px;
        padding: 3px 10px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        white-space: nowrap;
    }
    .btn-pilih-semua:hover { background: #1565C0; color: #fff; }
</style>
@endpush

@push('scripts')
<script>
    function generateSlots(jamBuka, jamTutup, durasi) {
        const slots = [];
        let [startH, startM] = jamBuka.split(':').map(Number);
        let [endH, endM] = jamTutup.split(':').map(Number);
        const endTotal = endH * 60 + endM;
        let current = startH * 60 + startM;
        while (current + durasi <= endTotal) {
            const dari = `${String(Math.floor(current/60)).padStart(2,'0')}:${String(current%60).padStart(2,'0')}`;
            const sampai = `${String(Math.floor((current+durasi)/60)).padStart(2,'0')}:${String((current+durasi)%60).padStart(2,'0')}`;
            slots.push(`${dari}-${sampai}`);
            current += durasi;
        }
        return slots;
    }

    function rupiah(angka) {
        return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
    }

    function renderSlot(lapId, lapNama, lapHarga, checkedSlots, jamBuka, jamTutup, durasi) {
        const diskon = parseInt(document.getElementById('diskon_input').value) || 0;
        const hargaAsal = parseInt(lapHarga);
        const hargaSetelah = hargaAsal - (hargaAsal * diskon / 100);
        const slots = generateSlots(jamBuka, jamTutup, parseInt(durasi));

        const existing = document.getElementById(`slot_section_${lapId}`);
        if (existing) existing.remove();

        const section = document.createElement('div');
        section.className = 'slot-section';
        section.id = `slot_section_${lapId}`;

        section.innerHTML = `
            <div class="slot-section-header">
                <div class="slot-section-title">
                    ${lapNama}
                    <span class="harga-coret">${rupiah(hargaAsal)}</span>
                    <i class="fas fa-arrow-right" style="font-size:10px;color:#94a3b8;"></i>
                    <span class="harga-diskon">${rupiah(hargaSetelah)}</span>
                    <span style="color:#94a3b8;font-size:11px;">(${diskon}% off)</span>
                </div>
                <button type="button" class="btn-pilih-semua" onclick="pilihSemua(${lapId})">Pilih Semua</button>
            </div>
            <div class="slot-grid">
                ${slots.map((slot, i) => `
                    <div class="form-check">
                        <input class="form-check-input slot-cb-${lapId}" type="checkbox"
                            name="slots[${lapId}][]" value="${slot}"
                            id="slot_${lapId}_${i}"
                            ${checkedSlots.includes(slot) ? 'checked' : ''}>
                        <label class="form-check-label" for="slot_${lapId}_${i}">${slot}</label>
                    </div>
                `).join('')}
            </div>
        `;

        document.getElementById('slot_container').appendChild(section);
    }

    function toggleSlot(cb) {
        if (cb.checked) {
            renderSlot(cb.value, cb.dataset.nama, cb.dataset.harga, [], cb.dataset.jamBuka, cb.dataset.jamTutup, cb.dataset.durasi);
        } else {
            const sec = document.getElementById(`slot_section_${cb.value}`);
            if (sec) sec.remove();
        }
    }

    function pilihSemua(lapId) {
        const cbs = document.querySelectorAll(`.slot-cb-${lapId}`);
        const allChecked = Array.from(cbs).every(cb => cb.checked);
        cbs.forEach(cb => cb.checked = !allChecked);
    }

    document.getElementById('diskon_input').addEventListener('input', function() {
        document.querySelectorAll('.lap-cb:checked').forEach(cb => {
            const existing = document.getElementById(`slot_section_${cb.value}`);
            let oldChecked = [];
            if (existing) {
                existing.querySelectorAll(`.slot-cb-${cb.value}:checked`).forEach(c => {
                    oldChecked.push(c.value);
                });
            }
            renderSlot(cb.value, cb.dataset.nama, cb.dataset.harga, oldChecked, cb.dataset.jamBuka, cb.dataset.jamTutup, cb.dataset.durasi);
        });
    });

    document.querySelectorAll('.lap-cb').forEach(cb => {
        cb.addEventListener('change', function() {
            if (document.querySelectorAll('.lap-cb:checked').length > 0) {
                document.querySelector('.lapangan-checkbox-grid').style.border = '1px solid #dee2e6';
                document.getElementById('lapangan-error')?.remove();
            }
        });
    });

    document.getElementById('formTambah').addEventListener('submit', function(e) {
        let hasError = false;

        const namaPromo = document.querySelector('[name="nama_promo"]');
        if (!namaPromo.value.trim()) {
            namaPromo.classList.add('is-invalid');
            if (!document.getElementById('error-nama')) {
                const el = document.createElement('div');
                el.id = 'error-nama';
                el.className = 'invalid-feedback d-block';
                el.textContent = 'Nama promo wajib diisi.';
                namaPromo.insertAdjacentElement('afterend', el);
            }
            hasError = true;
        } else {
            namaPromo.classList.remove('is-invalid');
            document.getElementById('error-nama')?.remove();
        }

        const diskon = document.getElementById('diskon_input');
        if (!diskon.value || diskon.value < 1 || diskon.value > 100) {
            diskon.classList.add('is-invalid');
            if (!document.getElementById('error-diskon')) {
                const el = document.createElement('div');
                el.id = 'error-diskon';
                el.className = 'invalid-feedback d-block';
                el.textContent = 'Besaran diskon wajib diisi (1-100).';
                diskon.insertAdjacentElement('afterend', el);
            }
            hasError = true;
        } else {
            diskon.classList.remove('is-invalid');
            document.getElementById('error-diskon')?.remove();
        }

        const tglMulai = document.querySelector('[name="tanggal_mulai"]');
        if (!tglMulai.value) {
            tglMulai.classList.add('is-invalid');
            if (!document.getElementById('error-tgl-mulai')) {
                const el = document.createElement('div');
                el.id = 'error-tgl-mulai';
                el.className = 'invalid-feedback d-block';
                el.textContent = 'Tanggal mulai wajib diisi.';
                tglMulai.insertAdjacentElement('afterend', el);
            }
            hasError = true;
        } else {
            tglMulai.classList.remove('is-invalid');
            document.getElementById('error-tgl-mulai')?.remove();
        }

        const tglAkhir = document.querySelector('[name="tanggal_berakhir"]');
        if (!tglAkhir.value) {
            tglAkhir.classList.add('is-invalid');
            if (!document.getElementById('error-tgl-akhir')) {
                const el = document.createElement('div');
                el.id = 'error-tgl-akhir';
                el.className = 'invalid-feedback d-block';
                el.textContent = 'Tanggal berakhir wajib diisi.';
                tglAkhir.insertAdjacentElement('afterend', el);
            }
            hasError = true;
        } else {
            tglAkhir.classList.remove('is-invalid');
            document.getElementById('error-tgl-akhir')?.remove();
        }

        const lapChecked = document.querySelectorAll('.lap-cb:checked');
        const grid = document.querySelector('.lapangan-checkbox-grid');
        if (lapChecked.length === 0) {
            grid.style.border = '2px solid #c62828';
            if (!document.getElementById('lapangan-error')) {
                const error = document.createElement('div');
                error.id = 'lapangan-error';
                error.style.cssText = 'color:#c62828;font-size:13px;margin-top:6px;';
                error.textContent = 'Pilih minimal 1 lapangan terlebih dahulu.';
                grid.insertAdjacentElement('afterend', error);
            }
            hasError = true;
        } else {
            grid.style.border = '1px solid #dee2e6';
            document.getElementById('lapangan-error')?.remove();
        }

        if (hasError) {
            e.preventDefault();
            return;
        }

        let lapanganTanpaSlot = null;
        lapChecked.forEach(cb => {
            if (!lapanganTanpaSlot) {
                const slots = document.querySelectorAll(`.slot-cb-${cb.value}:checked`);
                if (slots.length === 0) lapanganTanpaSlot = cb.dataset.nama;
            }
        });

        if (lapanganTanpaSlot) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Pilih Slot Jam!',
                text: `Pilih minimal 1 slot jam untuk lapangan ${lapanganTanpaSlot}.`,
                confirmButtonColor: '#1565C0'
            });
        }
    });
</script>
@endpush