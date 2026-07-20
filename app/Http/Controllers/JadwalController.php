<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $tanggal    = $request->tanggal ?? now()->toDateString();
        $lapanganId = $request->lapangan_id ?? null;

        $lapangans = Lapangan::where('status_lapangan', 'aktif')->get();

        $jadwals = Jadwal::with(['lapangan.promos' => function($q) {
            $q->withPivot('slots');
        }, 'bookings.pelanggan'])
            ->where('tanggal_jadwal', $tanggal)
            ->when($lapanganId, fn($q) => $q->where('lapangan_id', $lapanganId))
            ->orderBy('jam_mulai')
            ->get();

        $jadwalByLapangan = $jadwals->groupBy('lapangan_id');

        return view('admin.jadwal.index', compact(
            'jadwals', 'lapangans', 'jadwalByLapangan', 'tanggal', 'lapanganId'
        ));
    }

    public function destroy(Jadwal $jadwal)
    {
        if ($jadwal->status_jadwal === 'Penuh') {
            return redirect()->route('admin.jadwal.index')
                ->with('error', 'Slot yang sudah dipesan tidak bisa dihapus.');
        }

        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Slot jadwal berhasil dihapus.');
    }

   public function public(Request $request)
    {
        $kategori = $request->kategori ?? 'all';

        $lapangans = Lapangan::with('jenisLapangan')->orderBy('nama_lapangan')->get();
       
        $jadwals = Jadwal::with('lapangan')
            ->where('tanggal_jadwal', '>=', now()->toDateString())
            ->orderBy('tanggal_jadwal')
            ->get();

        return view('pelanggan.jadwal.index', compact('jadwals', 'lapangans', 'kategori'));
    }
}