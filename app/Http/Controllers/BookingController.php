<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['jadwal.lapangan', 'pelanggan'])->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

    public function create()
    {
        $jadwals = Jadwal::with(['lapangan.promos'])
            ->where('tanggal_jadwal', '>=', now()->toDateString())
            ->get();

        $jadwalData = $jadwals->map(function ($jadwal) {
            $promo = $jadwal->lapangan->promos
                ->where('status_promo', true)
                ->where('tanggal_mulai', '<=', $jadwal->tanggal_jadwal)
                ->where('tanggal_berakhir', '>=', $jadwal->tanggal_jadwal)
                ->first();

            $harga = $jadwal->lapangan->harga_lapangan;
            $hargaPromo = $promo
                ? $harga - ($harga * $promo->diskon_persen / 100)
                : null;

            return [
                'id'            => $jadwal->id,
                'lapangan_id'   => $jadwal->lapangan_id,
                'nama_lapangan' => $jadwal->lapangan->nama_lapangan,
                'tanggal'       => $jadwal->tanggal_jadwal,
                'jam_mulai'     => $jadwal->jam_mulai,
                'jam_selesai'   => $jadwal->jam_selesai,
                'status'        => $jadwal->status_jadwal,
                'harga'         => $harga,
                'ada_promo'     => $promo ? true : false,
                'harga_promo'   => $hargaPromo,
            ];
        });

        return view('pelanggan.booking.create', compact('jadwals', 'jadwalData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id'      => 'required|exists:jadwal,id',
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_hp'       => 'required|string',
        ], [
            'jadwal_id.required'      => 'Jadwal wajib dipilih.',
            'jadwal_id.exists'        => 'Jadwal tidak ditemukan.',
            'nama_pelanggan.required' => 'Nama lengkap wajib diisi.',
            'nomor_hp.required'       => 'Nomor WhatsApp wajib diisi.',
        ]);

        $nomorBersih = preg_replace('/\s+/', '', $request->nomor_hp);

        if (strlen($nomorBersih) < 10 || strlen($nomorBersih) > 15) {
            return back()->withErrors([
                'nomor_hp' => 'Nomor WhatsApp minimal 10 digit dan maksimal 15 digit.',
            ])->withInput();
        }

        $jadwal = Jadwal::with('lapangan')->findOrFail($request->jadwal_id);

        if ($jadwal->status_jadwal != 'Tersedia') {
            return back()->withErrors(['jadwal_id' => 'Jadwal ini sudah tidak tersedia.']);
        }

        $promo = $jadwal->lapangan->promos()
            ->where('status_promo', true)
            ->where('tanggal_mulai', '<=', $jadwal->tanggal_jadwal)
            ->where('tanggal_berakhir', '>=', $jadwal->tanggal_jadwal)
            ->first();

        $harga      = $jadwal->lapangan->harga_lapangan;
        $totalBayar = $promo
            ? $harga - ($harga * $promo->diskon_persen / 100)
            : $harga;

        $pelanggan = Pelanggan::firstOrCreate(
            ['nomor_hp' => $nomorBersih],
            ['nama_pelanggan' => $request->nama_pelanggan]
        );

        $booking = Booking::create([
            'jadwal_id'      => $jadwal->id,
            'pelanggan_id'   => $pelanggan->id,
            'jam_mulai'      => $jadwal->jam_mulai,
            'jam_selesai'    => $jadwal->jam_selesai,
            'total_bayar'    => $totalBayar,
            'status'         => 'Tertunda',
            'kode_booking'   => 'BSC-' . strtoupper(Str::random(6)) . '-' . now()->format('dmY'),
        ]);

        return redirect()->route('pembayaran.show', $booking->id);
    }

    public function cek()
    {
        return view('pelanggan.booking.riwayat');
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
            ->get();

        if ($bookings->isEmpty()) {
            return back()->withErrors([
                'nomor_hp' => 'Data booking tidak ditemukan.',
            ])->withInput();
        }

        return view('pelanggan.booking.status', compact('bookings'));
    }
}