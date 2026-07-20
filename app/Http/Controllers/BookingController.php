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
            'lapangan.promos' => function($q) {
                $q->withPivot('slots');
            },
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
                'jam_mulai'     => date('H:i', strtotime($jadwal->jam_mulai)),
                'jam_selesai'   => date('H:i', strtotime($jadwal->jam_selesai)),
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

        $jadwals = Jadwal::with(['lapangan.promos' => function($q) {
            $q->withPivot('slots');
        }])->whereIn('id', $request->jadwal_ids)->orderBy('jam_mulai')->get();

        foreach ($jadwals as $jadwal) {
            if ($jadwal->status_jadwal !== 'Tersedia') {
                return back()->withErrors([
                    'jadwal_ids' => 'Salah satu jadwal yang dipilih sudah tidak tersedia.'
                ])->withInput();
            }
        }
        // Cek berurutan
        for ($i = 1; $i < $jadwals->count(); $i++) {
            $prev = $jadwals[$i - 1];
            $curr = $jadwals[$i];
            if ($prev->jam_selesai !== $curr->jam_mulai) {
                return back()->withErrors([
                    'jadwal_ids' => 'Slot yang dipilih harus berurutan.'
                ])->withInput();
            }
        }

        $pelanggan = Pelanggan::updateOrCreate(
            ['nomor_hp' => $nomorBersih],
            ['nama_pelanggan' => $request->nama_pelanggan]
        );

        $kodeBooking  = 'BSC-' . strtoupper(Str::random(6)) . '-' . now()->format('dmY');
        $firstBooking = null;

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

            $harga      = $jadwal->lapangan->harga_lapangan;
            $totalBayar = $promo
                ? $harga - ($harga * $promo->diskon_persen / 100)
                : $harga;

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

        $this->sendWhatsAppNotification($firstBooking, $pelanggan, $jadwals);

        return redirect()->route('pembayaran.show', $firstBooking->id)

            ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');
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

        // Ambil booking dikelompokkan per kode_booking
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

    private function sendWhatsAppNotification($booking, $pelanggan, $jadwals = null)
    {
        $booking->loadMissing('jadwal.lapangan');

        if ($jadwals && $jadwals->count() > 1) {
            $jamMulai   = date('H.i', strtotime($jadwals->first()->jam_mulai));
            $jamSelesai = date('H.i', strtotime($jadwals->last()->jam_selesai));
        } else {
            $jamMulai   = date('H.i', strtotime($booking->jam_mulai));
            $jamSelesai = date('H.i', strtotime($booking->jam_selesai));
        }

        $totalBayar = $jadwals
            ? $jadwals->sum(function($j) use ($booking) {
                return Booking::where('jadwal_id', $j->id)
                    ->where('kode_booking', $booking->kode_booking)
                    ->value('total_bayar') ?? 0;
            })
            : $booking->total_bayar;

        $message =
            "⚽ *Bintang Sport Center*\n\n" .
            "Halo *{$pelanggan->nama_pelanggan}*,\n\n" .
            "Terima kasih. Pembayaran Anda telah *berhasil* diterima dan booking telah dikonfirmasi. 🎉\n\n" .

            "==================================\n" .
            "📋 *DETAIL BOOKING*\n" .
            "==================================\n" .
            "🔖 No. Booking : *{$booking->kode_booking}*\n" .
            "📅 Tanggal : " . \Carbon\Carbon::parse($booking->jadwal->tanggal_jadwal)
                    ->locale('id')
                    ->translatedFormat('l, d F Y') . "\n" .
            "⏰ Jam : " .
            \Carbon\Carbon::parse($booking->jam_mulai)->format('H.i') .
            " - " .
            \Carbon\Carbon::parse($booking->jam_selesai)->format('H.i') . "\n" .
            "🏟 Lapangan : {$booking->jadwal->lapangan->nama_lapangan}\n" .
            "💰 Total Pembayaran : *Rp" .
            number_format($booking->total_bayar, 0, ',', '.') . "*\n" .

            "==================================\n\n" .

            "✅ Status Booking : *Terkonfirmasi*\n\n" .

            "Mohon datang sesuai jadwal yang telah dipilih.\n"
            ."Tunjukkan kode booking kepada petugas saat melakukan check-in.\n\n" .

            "Terima kasih telah memilih *Bintang Sport Center* 🙏";

        app(WhatsappService::class)->kirim($pelanggan->nomor_hp, $message);
    }
}