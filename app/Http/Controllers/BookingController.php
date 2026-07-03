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
        $jadwals = Jadwal::with('lapangan')
            ->where('status_jadwal', 'Tersedia')
            ->where('tanggal_jadwal', '>=', now()->toDateString())
            ->get();
        return view('pelanggan.booking.create', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id'      => 'required|exists:jadwal,id',
            'nama_pelanggan' => 'required|string|max:255',
            'nomor_hp'       => 'required|string|min:10|max:15',
        ]);

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
            ['nomor_hp' => $request->nomor_hp],
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

    public function cekStatus(Request $request)
    {
        $request->validate([
            'nomor_hp' => 'required|string|min:10|max:15',
        ]);

        $pelanggan = Pelanggan::where('nomor_hp', $request->nomor_hp)->first();

        if (!$pelanggan) {
            return back()->withErrors(['nomor_hp' => 'Data booking tidak ditemukan.']);
        }

        $bookings = Booking::with(['jadwal.lapangan', 'pembayaran'])
            ->where('pelanggan_id', $pelanggan->id)
            ->latest()
            ->get();

        return view('pelanggan.booking.status', compact('bookings'));
    }
}