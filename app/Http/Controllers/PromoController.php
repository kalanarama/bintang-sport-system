<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Lapangan;
use App\Models\LapanganPromo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $totalAktif = Promo::where('status_promo', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->whereDate('tanggal_berakhir', '>=', now())
            ->count();

        $berakhirMingguIni = Promo::where('status_promo', true)
            ->whereDate('tanggal_berakhir', '>=', now())
            ->whereDate('tanggal_berakhir', '<=', now()->endOfWeek())
            ->count();

        $totalNonaktif = Promo::where('status_promo', false)->count();

        $query = Promo::with('lapangans')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $query->where('nama_promo', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'aktif') {
                $query->where('status_promo', true)
                    ->whereDate('tanggal_mulai', '<=', now())
                    ->whereDate('tanggal_berakhir', '>=', now());
            } else {
                $query->where(function($q) {
                    $q->where('status_promo', false)
                    ->orWhereDate('tanggal_berakhir', '<', now());
                });
            }
        }

        if ($request->filled('lapangan')) {
            $query->whereHas('lapangans', fn($q) =>
                $q->where('lapangan.id', $request->lapangan)
            );
        }

        $promos = $query->paginate(10)->appends($request->query());

        $lapangans = Lapangan::where('status_lapangan', 'aktif')->get();

        return view('admin.promo.index', compact(
            'totalAktif',
            'berakhirMingguIni',
            'totalNonaktif',
            'promos',
            'lapangans'
        ));
    }

    public function create()
    {
        $lapangans = Lapangan::where('status_lapangan', 'aktif')->get();
        return view('admin.promo.create', compact('lapangans'));
    }

    public function show($id)
    {
        $promo = Promo::with(['lapangans' => function($q) {
            $q->withPivot('slots');
        }])->findOrFail($id);
        return view('admin.promo.show', compact('promo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_promo'       => 'required|string|max:255',
            'diskon_persen'    => 'required|numeric|min:1|max:100',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'lapangan_ids'     => 'required|array|min:1',
            'lapangan_ids.*'   => 'exists:lapangan,id',
        ], [
            'nama_promo.required'             => 'Nama promo wajib diisi!',
            'nama_promo.string'               => 'Nama promo harus berupa teks.',
            'nama_promo.max'                  => 'Nama promo maksimal 255 karakter.',
            'diskon_persen.required'          => 'Besaran diskon wajib diisi!',
            'diskon_persen.numeric'           => 'Diskon harus berupa angka.',
            'diskon_persen.min'               => 'Diskon minimal 1%.',
            'diskon_persen.max'               => 'Diskon maksimal 100%.',
            'tanggal_mulai.required'          => 'Tanggal mulai wajib dipilih!',
            'tanggal_mulai.date'              => 'Format tanggal mulai tidak valid.',
            'tanggal_berakhir.required'       => 'Tanggal berakhir wajib dipilih!',
            'tanggal_berakhir.date'           => 'Format tanggal berakhir tidak valid.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai.',
            'lapangan_ids.required'           => 'Pilih minimal 1 lapangan!',
            'lapangan_ids.array'              => 'Format lapangan tidak valid.',
            'lapangan_ids.min'                => 'Pilih minimal 1 lapangan.',
            'lapangan_ids.*.exists'           => 'Lapangan yang dipilih tidak terdaftar.',
        ]);

        $promo = Promo::create([
            'nama_promo'       => $request->nama_promo,
            'diskon_persen'    => $request->diskon_persen,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
            'status_promo'     => true,
        ]);

        foreach ($request->lapangan_ids as $lapanganId) {
            $slots = $request->slots[$lapanganId] ?? [];
            LapanganPromo::create([
                'promo_id'    => $promo->id,
                'lapangan_id' => $lapanganId,
                'slots'       => $slots,
            ]);
        }

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $promo     = Promo::with('lapangans')->findOrFail($id);
        $lapangans = Lapangan::where('status_lapangan', 'aktif')->get();
        return view('admin.promo.edit', compact('promo', 'lapangans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_promo'       => 'required|string|max:255',
            'diskon_persen'    => 'required|numeric|min:1|max:100',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
            'lapangan_ids'     => 'required|array|min:1',
            'lapangan_ids.*'   => 'exists:lapangan,id',
        ], [
            'nama_promo.required'             => 'Nama promo wajib diisi!',
            'nama_promo.string'               => 'Nama promo harus berupa teks.',
            'nama_promo.max'                  => 'Nama promo maksimal 255 karakter.',
            'diskon_persen.required'          => 'Besaran diskon wajib diisi!',
            'diskon_persen.numeric'           => 'Diskon harus berupa angka.',
            'diskon_persen.min'               => 'Diskon minimal 1%.',
            'diskon_persen.max'               => 'Diskon maksimal 100%.',
            'tanggal_mulai.required'          => 'Tanggal mulai wajib dipilih!',
            'tanggal_mulai.date'              => 'Format tanggal mulai tidak valid.',
            'tanggal_berakhir.required'       => 'Tanggal berakhir wajib dipilih!',
            'tanggal_berakhir.date'           => 'Format tanggal berakhir tidak valid.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal mulai.',
            'lapangan_ids.required'           => 'Pilih minimal 1 lapangan!',
            'lapangan_ids.array'              => 'Format lapangan tidak valid.',
            'lapangan_ids.min'                => 'Pilih minimal 1 lapangan.',
            'lapangan_ids.*.exists'           => 'Lapangan yang dipilih tidak terdaftar.',
        ]);

        $promo = Promo::findOrFail($id);
        $promo->update([
            'nama_promo'       => $request->nama_promo,
            'diskon_persen'    => $request->diskon_persen,
            'tanggal_mulai'    => $request->tanggal_mulai,
            'tanggal_berakhir' => $request->tanggal_berakhir,
        ]);

        LapanganPromo::where('promo_id', $promo->id)->delete();
        foreach ($request->lapangan_ids as $lapanganId) {
            $slots = $request->slots[$lapanganId] ?? [];
            LapanganPromo::create([
                'promo_id'    => $promo->id,
                'lapangan_id' => $lapanganId,
                'slots'       => $slots,
            ]);
        }

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        LapanganPromo::where('promo_id', $promo->id)->delete();
        $promo->delete();

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil dihapus!');
    }
}