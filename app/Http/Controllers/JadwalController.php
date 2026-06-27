<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::with('lapangan')->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $lapangans = Lapangan::where('status_lapangan', 'aktif')->get(); 
        return view('admin.jadwal.create', compact('lapangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id'    => 'required|exists:lapangan,id',
            'tanggal_jadwal' => 'required|date|after_or_equal:today',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
        ]);

        Jadwal::create([
            'lapangan_id'    => $request->lapangan_id,
            'tanggal_jadwal' => $request->tanggal_jadwal,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'    => $request->jam_selesai,
            'status_jadwal'  => true, 
        ]);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil disimpan');
    }

    public function edit(Jadwal $jadwal)
    {
        $lapangans = Lapangan::where('status_lapangan', 'aktif')->get();
        return view('admin.jadwal.edit', compact('jadwal', 'lapangans'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'lapangan_id'    => 'required|exists:lapangan,id',
            'tanggal_jadwal' => 'required|date',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
        ]);

        $jadwal->update($request->only([
            'lapangan_id', 'tanggal_jadwal', 'jam_mulai', 'jam_selesai', 'status_jadwal'
        ]));

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    public function public()
    {
        $jadwals = Jadwal::with('lapangan')
            ->where('tanggal_jadwal', '>=', now()->toDateString())
            ->orderBy('tanggal_jadwal')
            ->get();
        return view('pelanggan.jadwal.index', compact('jadwals'));
    }
}