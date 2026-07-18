<?php

namespace App\Http\Controllers;

use App\Models\JenisLapangan;
use Illuminate\Http\Request;

class JenisLapanganController extends Controller
{
    public function index()
    {
        $jenisLapangans = JenisLapangan::latest()->paginate(10);

        return view('admin.jenisLapangan.index', compact('jenisLapangans'));
    }

    public function edit($id)
{
    $jenisLapangan = JenisLapangan::findOrFail($id);

    return view('admin.jenisLapangan.edit', compact('jenisLapangan'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'nama_jenis_lapangan' => 'required|string|max:255',
        'harga_per_jam' => 'required|numeric|min:0',
    ]);

    $jenisLapangan = JenisLapangan::findOrFail($id);

    $jenisLapangan->update([
        'nama_jenis_lapangan' => $request->nama_jenis_lapangan,
        'harga_per_jam' => $request->harga_per_jam,
    ]);

    return redirect()
        ->route('admin.jenisLapangan.index')
        ->with('success', 'Jenis lapangan berhasil diperbarui.');
}
}
