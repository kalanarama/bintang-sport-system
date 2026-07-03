<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::with('lapangans')->get();
        return view('admin.promo.index', compact('promos'));
    }

    public function create()
    {
        $lapangans = Lapangan::where('status_lapangan', 'Aktif')->get();
        return view('admin.promo.create', compact('lapangans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_promo'       => 'required|string|max:255',
            'diskon_persen'    => 'required|numeric|min:1|max:100',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'lapangan_ids'     => 'required|array|min:1',
            'lapangan_ids.*'   => 'exists:lapangan,id',
        ]);

        $promo = Promo::create([
            'nama_promo'       => $request->nama_promo,
            'diskon_persen'    => $request->diskon_persen,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'status_promo'     => $request->has('status_promo') ? true : false,
        ]);

        $promo->lapangans()->sync($request->lapangan_ids);

        return redirect()->route('promo.index')
            ->with('success', 'Promo berhasil disimpan');
    }

    public function edit(Promo $promo)
    {
        $lapangans = Lapangan::where('status_lapangan', 'Aktif')->get();
        return view('admin.promo.edit', compact('promo', 'lapangans'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'nama_promo'       => 'required|string|max:255',
            'diskon_persen'    => 'required|numeric|min:1|max:100',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'lapangan_ids'     => 'required|array|min:1',
            'lapangan_ids.*'   => 'exists:lapangan,id',
        ]);

        $promo->update([
            'nama_promo'       => $request->nama_promo,
            'diskon_persen'    => $request->diskon_persen,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'status_promo'     => $request->has('status_promo') ? true : false,
        ]);

        $promo->lapangans()->sync($request->lapangan_ids);

        return redirect()->route('promo.index')
            ->with('success', 'Promo berhasil diperbarui');
    }

    public function destroy(Promo $promo)
    {
        $promo->lapangans()->detach();
        $promo->delete();
        return redirect()->route('promo.index')
            ->with('success', 'Promo berhasil dihapus');
    }
}