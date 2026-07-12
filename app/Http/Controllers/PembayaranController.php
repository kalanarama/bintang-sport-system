<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Xendit\Configuration;
use Xendit\PaymentRequest\PaymentRequestApi;
use Xendit\PaymentRequest\PaymentRequestParameters;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
    }

    public function show($bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->findOrFail($bookingId);

        if ($booking->status !== 'Tertunda') {
            return redirect()->route('pembayaran.sukses', $bookingId);
        }

        if (empty($booking->qris_string)) {
            $this->generateQris($booking);
        }

        return view('pelanggan.pembayaran.show', compact('booking'));
    }

    private function generateQris(Booking $booking)
    {
        try {
            $apiInstance = new PaymentRequestApi();

            $body = new PaymentRequestParameters([
                'reference_id' => 'BOOKING-' . $booking->id . '-' . time(),
                'amount'   => (float) $booking->total_bayar,
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
            // hari ini
            dd($response);

            // --- Ambil QR string ---
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

            $booking->update([
                'qris_string'      => $qrString,
                'qris_request_id'  => $response->getId(),
                'qris_expired_at'  => now()->addMinutes(10),
            ]);

            Log::info('QRIS generated for booking', ['booking_id' => $booking->id]);
        } catch (Exception $e) {
            Log::error('Gagal generate QRIS', [
                'booking_id' => $booking->id,
                'error'      => $e->getMessage(),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'trace'      => $e->getTraceAsString(),
            ]);
            $booking->update([
                'qris_string'     => null,
                'qris_request_id' => null,
            ]);
        }
    }

    public function proses(Request $request, $bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->findOrFail($bookingId);

        if ($booking->status !== 'Tertunda') {
            return redirect()->route('pembayaran.sukses', $bookingId);
        }

        if ($booking->pembayaran()->exists()) {
            return redirect()->route('pembayaran.sukses', $bookingId);
        }

        if ($booking->qris_request_id) {
            try {
                $apiInstance = new PaymentRequestApi();
                $response = $apiInstance->getPaymentRequestByID($booking->qris_request_id, null);
                if ($response && $response->getStatus() === 'SUCCEEDED') {
                    $this->tandaiBerhasil($booking);
                    return redirect()->route('pembayaran.sukses', $booking->id);
                }
            } catch (Exception $e) {
                Log::warning('Gagal cek status pembayaran', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            }
        }

        return back()->with('info', 'Pembayaran belum diterima. Silakan coba lagi setelah membayar.');
    }

    public function simulasi($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'Berhasil') {
            return redirect()->route('pembayaran.sukses', $booking->id)
                ->with('info', 'Booking sudah berhasil.');
        }

        if ($booking->pembayaran()->exists()) {
            return redirect()->route('pembayaran.sukses', $booking->id)
                ->with('info', 'Pembayaran sudah dicatat.');
        }

        try {
            $this->tandaiBerhasil($booking);
            return redirect()->route('pembayaran.sukses', $booking->id)
                ->with('success', 'Pembayaran berhasil (simulasi manual)!');
        } catch (Exception $e) {
            Log::error('Simulasi gagal', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memproses simulasi: ' . $e->getMessage());
        }
    }

    private function tandaiBerhasil(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            if ($booking->pembayaran()->exists()) {
                return;
            }

            Pembayaran::create([
                'booking_id'         => $booking->id,
                'metode_pembayaran'  => 'QRIS',
                'tanggal_pembayaran' => now()->toDateString(),
                'total_pembayaran'   => $booking->total_bayar,
                'status_pembayaran'  => 'Berhasil',
            ]);

            $booking->update(['status' => 'Berhasil']);

            Jadwal::where('id', $booking->jadwal_id)->update(['status_jadwal' => 'Penuh']);

            Notifikasi::create([
                'booking_id'      => $booking->id,
                'pesan'           => "Booking berhasil! Kode: {$booking->kode_booking}. Lapangan: {$booking->jadwal->lapangan->nama_lapangan}, Tanggal: {$booking->jadwal->tanggal_jadwal}, Jam: {$booking->jam_mulai} - {$booking->jam_selesai}.",
                'tanggal_kirim'   => now(),
                'status_terkirim' => true,
            ]);
        });
    }

    public function webhook(Request $request)
    {
        $token = $request->header('x-callback-token');
        if ($token !== config('services.xendit.webhook_token')) {
            abort(403);
        }

        $event = $request->input('event');
        $data = $request->input('data', []);

        if ($event === 'payment.succeeded' && ($data['status'] ?? null) === 'SUCCEEDED') {
            $paymentRequestId = $data['payment_request_id'] ?? null;
            if ($paymentRequestId) {
                $booking = Booking::where('qris_request_id', $paymentRequestId)->first();
                if ($booking && $booking->status === 'Tertunda') {
                    $this->tandaiBerhasil($booking);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function sukses($bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])
            ->findOrFail($bookingId);

        return view('pelanggan.pembayaran.sukses', compact('booking'));
    }
}