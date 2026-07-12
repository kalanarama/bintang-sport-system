<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Booking;
use App\Models\Jadwal;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\PaymentRequest\PaymentRequestApi;
use Xendit\PaymentRequest\PaymentRequestParameters;
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
        $totalBayar = $promo ? $harga - ($harga * $promo->diskon_persen / 100) : $harga;

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

        try {
            Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
            $apiInstance = new PaymentRequestApi();

            $body = new PaymentRequestParameters([
                'reference_id' => 'BOOKING-' . $booking->id . '-' . time(),
                'amount'   => (float) $totalBayar,
                'currency' => 'IDR',
                'country' => 'ID',
                'payment_method' => [
                    'type'        => 'QR_CODE',
                    'reusability' => 'ONE_TIME_USE',
                    'qr_code'     => [
                        'channel_code' => 'QRIS'
                    ]
                ],
                'metadata' => [
                    'booking_id'   => $booking->id,
                    'kode_booking' => $booking->kode_booking,
                ],
            ]);

            $response = $apiInstance->createPaymentRequest(null, null, $body);

            $booking->qris_request_id = $response->getId();

            // Ambil QR string
            $qrString = null;
            $paymentMethod = $response->getPaymentMethod();
            if ($paymentMethod) {
                $qrCode = $paymentMethod->getQrCode();
                if ($qrCode && method_exists($qrCode, 'getChannelProperties')) {
                    $props = $qrCode->getChannelProperties();
                    $qrString = $props['qr_string'] ?? null;
                }
                if (!$qrString) {
                    $actions = $paymentMethod->getActions() ?? [];
                    foreach ($actions as $action) {
                        if (isset($action['url']) && strpos($action['url'], 'qris') !== false) {
                            $qrString = $action['url'];
                            break;
                        }
                    }
                }
            }

            $booking->qris_string = $qrString;
            $booking->qris_expired_at = now()->addMinutes(10);
            $booking->save();

            // Kirim WhatsApp sukses
            $this->sendWhatsAppNotification($booking, $pelanggan, $totalBayar, 'sukses');

            Log::info('Xendit Payment Request created', [
                'booking_id'          => $booking->id,
                'payment_request_id'  => $response->getId(),
                'qr_code_url'         => $qrString,
            ]);

            return redirect()->route('pembayaran.show', $booking->id)
                ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');

        } catch (Exception $e) {
            Log::error('Xendit Payment Request gagal dibuat', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'trace'      => $e->getTraceAsString(),
            ]);

            $booking->qris_request_id = null;
            $booking->qris_string = null;
            $booking->save();

            // Kirim WhatsApp gagal
            $this->sendWhatsAppNotification($booking, $pelanggan, $totalBayar, 'gagal');

            return redirect()->route('pembayaran.show', $booking->id)
                ->with('error', 'Gagal membuat QRIS: ' . $e->getMessage());
        }
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
     * Kirim notifikasi WhatsApp via FONNTE
     */
    private function sendWhatsAppNotification($booking, $pelanggan, $totalBayar, $status = 'sukses') {
    $token = config('services.fonnte.token');
    $target = $pelanggan->nomor_hp;

    // Konversi nomor 08xx → 628xx
    if (substr($target, 0, 1) === '0') {
        $target = '62' . substr($target, 1);
    }

    // Pastikan relasi tersedia
    $booking->loadMissing('jadwal.lapangan');

    if ($status === 'sukses') {

        $message =
        "⚽ *Bintang Sport Center*\n\n" .
        "Halo *{$pelanggan->nama_pelanggan}*,\n" .
        "Booking Anda telah berhasil dibuat.\n\n" .

        "========================\n" .
        "📋 *DETAIL BOOKING*\n" .
        "========================\n" .
        "🔖 No. Booking : *{$booking->kode_booking}*\n" .
        "📅 Tanggal : " . \Carbon\Carbon::parse($booking->jadwal->tanggal_jadwal)
                                    ->locale('id')
                                    ->translatedFormat('l, d F Y') . "\n" .
        "⏰ Jam : " . \Carbon\Carbon::parse($booking->jam_mulai)->format('H.i') .
                        " - " .
                        \Carbon\Carbon::parse($booking->jam_selesai)->format('H.i') .
                        "\n" .
        "🏟 Lapangan : {$booking->jadwal->lapangan->nama_lapangan}\n" .
        "========================\n\n" .

        "⚠️ *PENTING*\n" .
        "1. Silakan lakukan pembayaran melalui QRIS yang tersedia.\n" .
        "2. Booking akan otomatis dibatalkan apabila pembayaran belum dilakukan sebelum batas waktu.\n\n" .

        "Terima kasih 🙏\n" .
        "*Bintang Sport Center*";

    } else {

        $message =
        "🏸 *Bintang Sport Center*\n\n" .
        "Halo *{$pelanggan->nama_pelanggan}*,\n\n" .
        "Booking dengan kode *{$booking->kode_booking}* telah tercatat.\n\n" .

        "Namun saat ini terjadi kendala dalam pembuatan pembayaran QRIS.\n" .
        "Silakan hubungi admin atau coba beberapa saat lagi.\n\n" .

        "Terima kasih 🙏\n" .
        "*Bintang Sport Center*";
    }

        Http::withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
        ]);
    }
}