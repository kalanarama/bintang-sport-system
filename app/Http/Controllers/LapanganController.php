<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::paginate(10);
        return view('admin.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        if ($request->jam_buka) {
            $request->merge(['jam_buka' => date('H:i', strtotime($request->jam_buka))]);
        }
        if ($request->jam_tutup) {
            $request->merge(['jam_tutup' => date('H:i', strtotime($request->jam_tutup))]);
        }

        $request->validate([
            'nama_lapangan'   => 'required|string|max:255',
            'jenis_lapangan'  => 'required|in:Badminton,Futsal A,Futsal B,Basket',
            'harga_lapangan'  => 'required|numeric|min:1',
            'jam_buka'        => 'required|date_format:H:i',
            'jam_tutup'       => 'required|date_format:H:i|after:jam_buka',
            'durasi_slot'     => 'required|in:60,120',
            'foto_lapangan'   => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'status_lapangan' => 'required|in:aktif,nonaktif',
        ], [
            'nama_lapangan.required'   => 'Nama lapangan wajib diisi.',
            'jenis_lapangan.required'  => 'Jenis lapangan wajib dipilih.',
            'harga_lapangan.required'  => 'Harga lapangan wajib diisi.',
            'harga_lapangan.numeric'   => 'Harga lapangan harus berupa angka.',
            'harga_lapangan.min'       => 'Harga lapangan minimal Rp1.',
            'jam_buka.required'        => 'Jam buka wajib diisi.',
            'jam_buka.date_format'     => 'Format jam buka tidak valid.',
            'jam_tutup.required'       => 'Jam tutup wajib diisi.',
            'jam_tutup.date_format'    => 'Format jam tutup tidak valid.',
            'jam_tutup.after'          => 'Jam tutup harus setelah jam buka.',
            'durasi_slot.required'     => 'Durasi slot wajib dipilih.',
            'durasi_slot.in'           => 'Durasi slot tidak valid.',
            'foto_lapangan.required'   => 'Foto lapangan wajib diunggah.',
            'foto_lapangan.image'      => 'File harus berupa gambar.',
            'foto_lapangan.mimes'      => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_lapangan.max'        => 'Ukuran foto maksimal 5MB.',
            'status_lapangan.required' => 'Status lapangan wajib dipilih.',
        ]);

        $data = $request->only([
            'nama_lapangan', 'jenis_lapangan', 'harga_lapangan',
            'jam_buka', 'jam_tutup', 'durasi_slot', 'status_lapangan'
        ]);

        if ($request->hasFile('foto_lapangan')) {
            $extension = $request->file('foto_lapangan')->getClientOriginalExtension();
            $filename  = time() . '.' . $extension;
            $request->file('foto_lapangan')->move(public_path('img/lapangan'), $filename);
            $data['foto_lapangan'] = 'img/lapangan/' . $filename;
        }

        $lapangan = Lapangan::create($data);

        if ($lapangan->status_lapangan === 'aktif') {
            $this->generateJadwal($lapangan);
        }

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Data lapangan berhasil disimpan dan jadwal telah digenerate.');
    }

    public function show(Lapangan $lapangan)
    {
        return view('admin.lapangan.show', compact('lapangan'));
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        if ($request->jam_buka) {
            $request->merge(['jam_buka' => date('H:i', strtotime($request->jam_buka))]);
        }
        if ($request->jam_tutup) {
            $request->merge(['jam_tutup' => date('H:i', strtotime($request->jam_tutup))]);
        }

        $request->validate([
            'nama_lapangan'   => 'required|string|max:255',
            'jenis_lapangan' => 'required|in:Badminton,Futsal A,Futsal B,Basket',
            'harga_lapangan'  => 'required|numeric|min:1',
            'jam_buka'        => 'required|date_format:H:i',
            'jam_tutup'       => 'required|date_format:H:i|after:jam_buka',
            'durasi_slot'     => 'required|in:60,120',
            'foto_lapangan'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'status_lapangan' => 'required|in:aktif,nonaktif',
        ], [
            'nama_lapangan.required'   => 'Nama lapangan wajib diisi.',
            'jenis_lapangan.required'  => 'Jenis lapangan wajib dipilih.',
            'harga_lapangan.required'  => 'Harga lapangan wajib diisi.',
            'harga_lapangan.numeric'   => 'Harga lapangan harus berupa angka.',
            'harga_lapangan.min'       => 'Harga lapangan minimal Rp1.',
            'jam_buka.required'        => 'Jam buka wajib diisi.',
            'jam_buka.date_format'     => 'Format jam buka tidak valid.',
            'jam_tutup.required'       => 'Jam tutup wajib diisi.',
            'jam_tutup.date_format'    => 'Format jam tutup tidak valid.',
            'jam_tutup.after'          => 'Jam tutup harus setelah jam buka.',
            'durasi_slot.required'     => 'Durasi slot wajib dipilih.',
            'durasi_slot.in'           => 'Durasi slot tidak valid.',
            'foto_lapangan.image'      => 'File harus berupa gambar.',
            'foto_lapangan.mimes'      => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_lapangan.max'        => 'Ukuran foto maksimal 5MB.',
            'status_lapangan.required' => 'Status lapangan wajib dipilih.',
        ]);

        // Cek perubahan SEBELUM update
        $jadwalBerubah = $lapangan->jam_buka !== $request->jam_buka
            || $lapangan->jam_tutup !== $request->jam_tutup
            || (int)$lapangan->durasi_slot !== (int)$request->durasi_slot;

        $statusLama = $lapangan->status_lapangan;
        $statusBaru = $request->status_lapangan;

        $data = $request->only([
            'nama_lapangan', 'jenis_lapangan', 'harga_lapangan',
            'jam_buka', 'jam_tutup', 'durasi_slot', 'status_lapangan'
        ]);

        if ($request->hasFile('foto_lapangan')) {
            if ($lapangan->foto_lapangan && File::exists(public_path($lapangan->foto_lapangan))) {
                File::delete(public_path($lapangan->foto_lapangan));
            }
            $extension = $request->file('foto_lapangan')->getClientOriginalExtension();
            $filename  = time() . '.' . $extension;
            $request->file('foto_lapangan')->move(public_path('img/lapangan'), $filename);
            $data['foto_lapangan'] = 'img/lapangan/' . $filename;
        }

        $lapangan->update($data);

        // Kalau dinonaktifkan: hapus jadwal Tersedia ke depan
        if ($statusLama === 'aktif' && $statusBaru === 'nonaktif') {
            Jadwal::where('lapangan_id', $lapangan->id)
                ->where('status_jadwal', 'Tersedia')
                ->where('tanggal_jadwal', '>=', now()->toDateString())
                ->delete();
        }

        // Kalau diaktifkan kembali: generate jadwal lagi
        elseif ($statusLama === 'nonaktif' && $statusBaru === 'aktif') {
            $this->generateJadwal($lapangan);
        }

        // Kalau tetap aktif tapi jam/durasi berubah: hapus Tersedia ke depan lalu generate ulang
        elseif ($statusBaru === 'aktif' && $jadwalBerubah) {
            Jadwal::where('lapangan_id', $lapangan->id)
                ->where('status_jadwal', 'Tersedia')
                ->where('tanggal_jadwal', '>=', now()->toDateString())
                ->delete();
            $this->generateJadwal($lapangan);
        }

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Data lapangan berhasil diperbarui.');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->foto_lapangan && File::exists(public_path($lapangan->foto_lapangan))) {
            File::delete(public_path($lapangan->foto_lapangan));
        }

        Jadwal::where('lapangan_id', $lapangan->id)
            ->where('status_jadwal', 'Tersedia')
            ->where('tanggal_jadwal', '>=', now()->toDateString())
            ->delete();

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Data lapangan berhasil dihapus');
    }

    public function public(Request $request)
    {
        $lapangans = Lapangan::orderBy('jenis_lapangan')
                        ->orderBy('nama_lapangan')
                        ->get();

        $kategori = strtolower($request->query('kategori', 'all'));

        return view('pelanggan.jadwal.index', compact('lapangans', 'kategori'));
    }
    public function generateJadwal(Lapangan $lapangan)
    {
        $tanggalMulai = Carbon::today();
        $tanggalAkhir = Carbon::today()->addMonths(6);
        $durasi       = (int) $lapangan->durasi_slot;
        $jamBuka      = Carbon::parse($lapangan->jam_buka);
        $jamTutup     = Carbon::parse($lapangan->jam_tutup);
        $now          = now();
        $batch        = [];

        for ($date = $tanggalMulai->copy(); $date->lte($tanggalAkhir); $date->addDay()) {
            $jamMulai = $jamBuka->copy();

            while ($jamMulai->copy()->addMinutes($durasi)->lte($jamTutup)) {
                $jamSelesai = $jamMulai->copy()->addMinutes($durasi);

                $batch[] = [
                    'lapangan_id'    => $lapangan->id,
                    'tanggal_jadwal' => $date->toDateString(),
                    'jam_mulai'      => $jamMulai->format('H:i'),
                    'jam_selesai'    => $jamSelesai->format('H:i'),
                    'status_jadwal'  => 'Tersedia',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ];

                $jamMulai->addMinutes($durasi);
            }

            if (count($batch) >= 500) {
                Jadwal::insertOrIgnore($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            Jadwal::insertOrIgnore($batch);
        }
    }
}