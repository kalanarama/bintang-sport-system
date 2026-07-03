<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::all();
        return view('admin.lapangan.index', compact('lapangans'));
    }

    public function create()
    {
        return view('admin.lapangan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lapangan'   => 'required|string|max:255',
            'jenis_lapangan'  => 'required|string|max:255',
            'harga_lapangan'  => 'required|numeric|min:0',
            'status_lapangan' => 'required|in:Aktif,Nonaktif',
        ]);

        Lapangan::create($request->only([
            'nama_lapangan', 'jenis_lapangan', 'harga_lapangan', 'status_lapangan'
        ]));

        return redirect()->route('lapangan.index')
            ->with('success', 'Data lapangan berhasil disimpan');
    }

    public function edit(Lapangan $lapangan)
    {
        return view('admin.lapangan.edit', compact('lapangan'));
    }

    public function update(Request $request, Lapangan $lapangan)
    {
        $request->validate([
            'nama_lapangan'   => 'required|string|max:255',
            'jenis_lapangan'  => 'required|string|max:255',
            'harga_lapangan'  => 'required|numeric|min:0',
            'status_lapangan' => 'required|in:Aktif,Nonaktif',
        ]);

        $lapangan->update($request->only([
            'nama_lapangan', 'jenis_lapangan', 'harga_lapangan', 'status_lapangan'
        ]));

        return redirect()->route('lapangan.index')
            ->with('success', 'Data lapangan berhasil diperbarui');
    }

    public function destroy(Lapangan $lapangan)
    {
        $lapangan->delete();
        return redirect()->route('lapangan.index')
            ->with('success', 'Data lapangan berhasil dihapus');
    }
}