<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
            'jenis_lapangan'  => 'required|in:Badminton,Futsal,Basket',
            'harga_lapangan'  => 'required|numeric|min:1',
            'foto_lapangan'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'status_lapangan' => 'required|in:aktif,nonaktif',
        ], [
            'nama_lapangan.required'   => 'Nama lapangan wajib diisi.',
            'jenis_lapangan.required'  => 'Jenis lapangan wajib dipilih.',
            'harga_lapangan.required'  => 'Harga lapangan wajib diisi.',
            'harga_lapangan.numeric'   => 'Harga lapangan harus berupa angka.',
            'harga_lapangan.min'       => 'Harga lapangan minimal Rp1.',
            'foto_lapangan.image'      => 'File harus berupa gambar.',
            'foto_lapangan.mimes'      => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_lapangan.max'        => 'Ukuran foto maksimal 5MB.',
            'status_lapangan.required' => 'Status lapangan wajib dipilih.',
        ]);

        $data = $request->only([
            'nama_lapangan', 'jenis_lapangan', 'harga_lapangan', 'status_lapangan'
        ]);

        if ($request->hasFile('foto_lapangan')) {
            $extension = $request->file('foto_lapangan')->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $request->file('foto_lapangan')->move(public_path('img/lapangan'), $filename);
            $data['foto_lapangan'] = 'img/lapangan/' . $filename;
        }

        Lapangan::create($data);

        return redirect()->route('admin.lapangan.index')
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
            'jenis_lapangan'  => 'required|in:Badminton,Futsal,Basket',
            'harga_lapangan'  => 'required|numeric|min:1',
            'foto_lapangan'   => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'status_lapangan' => 'required|in:aktif,nonaktif',
        ], [
            'nama_lapangan.required'   => 'Nama lapangan wajib diisi.',
            'jenis_lapangan.required'  => 'Jenis lapangan wajib dipilih.',
            'harga_lapangan.required'  => 'Harga lapangan wajib diisi.',
            'harga_lapangan.numeric'   => 'Harga lapangan harus berupa angka.',
            'harga_lapangan.min'       => 'Harga lapangan minimal Rp1.',
            'foto_lapangan.image'      => 'File harus berupa gambar.',
            'foto_lapangan.mimes'      => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto_lapangan.max'        => 'Ukuran foto maksimal 5MB.',
            'status_lapangan.required' => 'Status lapangan wajib dipilih.',
        ]);

        $data = $request->only([
            'nama_lapangan', 'jenis_lapangan', 'harga_lapangan', 'status_lapangan'
        ]);

        if ($request->hasFile('foto_lapangan')) {
            if ($lapangan->foto_lapangan && File::exists(public_path($lapangan->foto_lapangan))) {
                File::delete(public_path($lapangan->foto_lapangan));
            }
            $extension = $request->file('foto_lapangan')->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $request->file('foto_lapangan')->move(public_path('img/lapangan'), $filename);
            $data['foto_lapangan'] = 'img/lapangan/' . $filename;
        }

        $lapangan->update($data);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Data lapangan berhasil diperbarui');
    }

    public function destroy(Lapangan $lapangan)
    {
        if ($lapangan->foto_lapangan && File::exists(public_path($lapangan->foto_lapangan))) {
            File::delete(public_path($lapangan->foto_lapangan));
        }

        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Data lapangan berhasil dihapus');
    }
}