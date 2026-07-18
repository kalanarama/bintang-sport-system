<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['jadwal.lapangan', 'pelanggan'])->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

    public function create()
    {
        $jadwals = Jadwal::with([
            'lapangan.promos',
            'lapangan.jenisLapangan'
        ])
        ->where('tanggal_jadwal', '>=', now()->toDateString())
        ->get();

        $jadwalData = $jadwals->map(function ($jadwal) {
            $promo = $jadwal->lapangan->promos
                ->where('status_promo', true)
                ->where('tanggal_mulai', '<=', $jadwal->tanggal_jadwal)
                ->where('tanggal_berakhir', '>=', $jadwal->tanggal_jadwal)
                ->first();

            $harga = $jadwal->lapangan->jenisLapangan->harga_per_jam;
            $hargaPromo = $promo
                ? $harga - ($harga * $promo->diskon_persen / 100)
                : null;

            return [
                'id'            => $jadwal->id,
                'lapangan_id'   => $jadwal->lapangan_id,
                'nama_lapangan' => $jadwal->lapangan->nama_lapangan,
                'tanggal'       => $jadwal->tanggal_jadwal,
                'jam_mulai'     => date('H.i', strtotime($jadwal->jam_mulai)),
                'jam_selesai'   => date('H.i', strtotime($jadwal->jam_selesai)),
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

        $harga = $jadwal->lapangan->jenisLapangan->harga_per_jam;
        $totalBayar = $promo ? $harga - ($harga * $promo->diskon_persen / 100) : $harga;

        $pelanggan = Pelanggan::updateOrCreate(
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

        // Arahkan ke halaman pembayaran (menampilkan QRIS statis)
        return redirect()->route('pembayaran.show', $booking->id)
            ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');
    }

    public function cek()
    {
        return view('pelanggan.riwayat');
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

    /**
     * Kirim notifikasi WhatsApp via FONNTE (sukses booking)
     */
    private function sendWhatsAppNotification($booking, $pelanggan)
    {
        $booking->loadMissing('jadwal.lapangan');

        $message =
            "⚽ *Bintang Sport Center*\n\n" .
            "Halo *{$pelanggan->nama_pelanggan}*,\n" .
            "Booking Anda telah berhasil dibuat.\n\n" .
            "==================================\n" .
            "📋 *DETAIL BOOKING*\n" .
            "==================================\n" .
            "🔖 No. Booking : *{$booking->kode_booking}*\n" .
            "📅 Tanggal : " . \Carbon\Carbon::parse($booking->jadwal->tanggal_jadwal)
                                    ->locale('id')
                                    ->translatedFormat('l, d F Y') . "\n" .
            "⏰ Jam : " . \Carbon\Carbon::parse($booking->jam_mulai)->format('H.i') .
                        " - " .
                        \Carbon\Carbon::parse($booking->jam_selesai)->format('H.i') .
                        "\n" .
            "🏟 Lapangan : {$booking->jadwal->lapangan->nama_lapangan}\n" .
            "💰 Total : Rp" . number_format($booking->total_bayar, 0, ',', '.') . "\n" .
            "==================================\n\n" .
            "⚠️ *PENTING*\n" .
            "Pastikan sudah mengecek jadwal dan datang tepat waktu.\n\n" .
            "Terima kasih 🙏\n" .
            "*Bintang Sport Center*";

        app(WhatsappService::class)->kirim($pelanggan->nomor_hp, $message);
    }
}