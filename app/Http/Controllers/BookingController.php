<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['jadwal.lapangan', 'pelanggan'])->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])->findOrFail($id);
        return view('admin.booking.show', compact('booking'));
    }

    public function create(Request $request)
    {
        $query = Jadwal::with(['lapangan.jenisLapangan', 'lapangan.promos' => function($q) {
            $q->withPivot('slots');
        }])->where('tanggal_jadwal', '>=', now()->toDateString());

        if ($request->lapangan_id) {
            $query->where('lapangan_id', $request->lapangan_id);
        }

        $jadwals = $query->get();

        $jadwalData = $jadwals->map(function ($jadwal) {
            $promo = $jadwal->lapangan->promos
                ->where('status_promo', true)
                ->where('tanggal_mulai', '<=', $jadwal->tanggal_jadwal)
                ->where('tanggal_berakhir', '>=', $jadwal->tanggal_jadwal)
                ->first();

            $harga = $jadwal->lapangan->jenisLapangan->harga_per_jam;
            $hargaPromo = $promo ? $harga - ($harga * $promo->diskon_persen / 100) : null;

            return [
                'id'            => $jadwal->id,
                'lapangan_id'   => $jadwal->lapangan_id,
                'nama_lapangan' => $jadwal->lapangan->nama_lapangan,
                'tanggal'       => $jadwal->tanggal_jadwal,
                'jam_mulai'     => date('H:i', strtotime($jadwal->jam_mulai)),
                'jam_selesai'   => date('H:i', strtotime($jadwal->jam_selesai)),
                'status'        => $jadwal->status_jadwal,
                'harga'         => $harga,
                'ada_promo'     => $promo ? true : false,
                'harga_promo'   => $hargaPromo,
            ];
        });

        $lapanganId = $request->lapangan_id;
        return view('pelanggan.booking.create', compact('jadwals', 'jadwalData', 'lapanganId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_ids'     => 'required|array|min:1',
            'jadwal_ids.*'   => 'exists:jadwal,id',
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_hp'       => 'required|string',
        ], [
            'jadwal_ids.required'     => 'Jadwal wajib dipilih.',
            'nama_pelanggan.required' => 'Nama lengkap wajib diisi.',
            'nomor_hp.required'       => 'Nomor WhatsApp wajib diisi.',
        ]);

        $nomorBersih = preg_replace('/\s+/', '', $request->nomor_hp);

        if (strlen($nomorBersih) < 10 || strlen($nomorBersih) > 15) {
            return back()->withErrors([
                'nomor_hp' => 'Nomor WhatsApp minimal 10 digit dan maksimal 15 digit.',
            ])->withInput();
        }

        $jadwals = Jadwal::with(['lapangan.jenisLapangan', 'lapangan.promos' => function($q) {
            $q->withPivot('slots');
        }])->whereIn('id', $request->jadwal_ids)->orderBy('jam_mulai')->get();

        foreach ($jadwals as $jadwal) {
            if ($jadwal->status_jadwal !== 'Tersedia') {
                return back()->withErrors([
                    'jadwal_ids' => 'Salah satu jadwal yang dipilih sudah tidak tersedia.'
                ])->withInput();
            }
        }

        $jadwalsSorted = $jadwals->sortBy(function($j) {
            return $j->tanggal_jadwal . ' ' . $j->jam_mulai;
        })->values();

        for ($i = 1; $i < $jadwalsSorted->count(); $i++) {
            $prev = $jadwalsSorted[$i - 1];
            $curr = $jadwalsSorted[$i];

            $prevSelesai = \Carbon\Carbon::parse($prev->tanggal_jadwal . ' ' . $prev->jam_selesai);
            $currMulai   = \Carbon\Carbon::parse($curr->tanggal_jadwal . ' ' . $curr->jam_mulai);

            if (!$prevSelesai->equalTo($currMulai)) {
                return back()->withErrors([
                    'jadwal_ids' => 'Slot yang dipilih harus berurutan.'
                ])->withInput();
            }
        }

        $pelanggan = Pelanggan::where('nomor_hp', $nomorBersih)->first();

        if (!$pelanggan) {
            $pelanggan = Pelanggan::create([
                'nomor_hp'       => $nomorBersih,
                'nama_pelanggan' => $request->nama_pelanggan,
            ]);
        }

        do {
            $kodeBooking = 'BSC-' . strtoupper(Str::random(8)) . '-' . now()->format('dmY');
        } while (Booking::where('kode_booking', $kodeBooking)->exists());

        $firstBooking = null;

        DB::transaction(function () use ($jadwals, $pelanggan, $kodeBooking, &$firstBooking) {
            foreach ($jadwals as $jadwal) {
                $promo = $jadwal->lapangan->promos
                    ->where('status_promo', true)
                    ->where('tanggal_mulai', '<=', $jadwal->tanggal_jadwal)
                    ->where('tanggal_berakhir', '>=', $jadwal->tanggal_jadwal)
                    ->first(function ($p) use ($jadwal) {
                        $slots = is_array($p->pivot->slots)
                            ? $p->pivot->slots
                            : json_decode($p->pivot->slots ?? '[]', true);
                        if (empty($slots)) return true;
                        $jamSlot = substr($jadwal->jam_mulai, 0, 5) . '-' . substr($jadwal->jam_selesai, 0, 5);
                        return in_array($jamSlot, $slots);
                    });

                $harga      = $jadwal->lapangan->jenisLapangan->harga_per_jam;
                $totalBayar = $promo
                    ? $harga - ($harga * $promo->diskon_persen / 100)
                    : $harga;

                Jadwal::where('id', $jadwal->id)->update(['status_jadwal' => 'Penuh']);

                $booking = Booking::create([
                    'jadwal_id'    => $jadwal->id,
                    'pelanggan_id' => $pelanggan->id,
                    'jam_mulai'    => $jadwal->jam_mulai,
                    'jam_selesai'  => $jadwal->jam_selesai,
                    'total_bayar'  => $totalBayar,
                    'status'       => 'Tertunda',
                    'kode_booking' => $kodeBooking,
                ]);

                if (!$firstBooking) $firstBooking = $booking;
            }
        });

        return redirect()->route('pembayaran.show', $firstBooking->id)
            ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');
    }

    public function cekPelanggan(Request $request)
    {
        $nomor = preg_replace('/\s+/', '', $request->nomor);
        $pelanggan = Pelanggan::where('nomor_hp', $nomor)->first();
        return response()->json(['nama' => $pelanggan?->nama_pelanggan]);
    }

    public function cek()
    {
        return view('pelanggan.booking.riwayat');
    }

    public function riwayatDariSession()
    {
        $nomorHp = session('nomor_hp') ?? request('nomor');

        if (!$nomorHp) {
            return redirect()->route('booking.cek');
        }

        $pelanggan = Pelanggan::where('nomor_hp', $nomorHp)->first();
        if (!$pelanggan) {
            return redirect()->route('booking.cek');
        }

        $bookings = Booking::with(['jadwal.lapangan', 'pembayaran'])
            ->where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->get()
            ->groupBy('kode_booking');

        return view('pelanggan.booking.status', compact('bookings', 'pelanggan'));
    }

    public function cekStatus(Request $request)
    {
        $request->validate([
            'nomor_hp' => 'required|string',
        ], [
            'nomor_hp.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        $nomorBersih = preg_replace('/\s+/', '', $request->nomor_hp);

        if (strlen($nomorBersih) < 10 || strlen($nomorBersih) > 15) {
            return back()->withErrors([
                'nomor_hp' => 'Nomor WhatsApp minimal 10 digit dan maksimal 15 digit.',
            ])->withInput();
        }

        $pelanggan = Pelanggan::where('nomor_hp', $nomorBersih)->first();
        if (!$pelanggan) {
            return back()->withErrors([
                'nomor_hp' => 'Data booking tidak ditemukan.',
            ])->withInput();
        }

        $bookings = Booking::with(['jadwal.lapangan', 'pembayaran'])
            ->where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->get()
            ->groupBy('kode_booking');

        if ($bookings->isEmpty()) {
            return back()->withErrors([
                'nomor_hp' => 'Data booking tidak ditemukan.',
            ])->withInput();
        }

        return view('pelanggan.booking.status', compact('bookings', 'pelanggan'));
    }

    public function batalkan($bookingId)
    {
        $booking = Booking::with('pelanggan')->findOrFail($bookingId);

        if ($booking->status !== 'Tertunda') {
            return back()->with('error', 'Booking tidak bisa dibatalkan.');
        }

        $semuaBooking = Booking::where('kode_booking', $booking->kode_booking)->get();
        foreach ($semuaBooking as $b) {
            $b->update(['status' => 'Dibatalkan']);
            Jadwal::where('id', $b->jadwal_id)->update(['status_jadwal' => 'Tersedia']);
        }

        session(['nomor_hp' => $booking->pelanggan->nomor_hp]);
        return redirect()->route('booking.status')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}