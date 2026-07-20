<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Models\Jadwal;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function show($bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->findOrFail($bookingId);

        if ($booking->status !== 'Tertunda' && !session('bayar_sukses')) {
            $booking->load('pelanggan');
            return redirect()->route('booking.status');
        }

        $semuaBooking = Booking::with([
                'jadwal.lapangan.jenisLapangan',
                'jadwal.lapangan.promos' => function($q) {
                    $q->withPivot('slots');
                }
            ])
            ->where('kode_booking', $booking->kode_booking)
            ->orderBy('jam_mulai')
            ->get();

        $totalKeseluruhan = $semuaBooking->sum('total_bayar');

        return view('pelanggan.pembayaran.show', compact('booking', 'semuaBooking', 'totalKeseluruhan'));
    }

    public function proses(Request $request, $bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->findOrFail($bookingId);

        if ($booking->status === 'Berhasil') {
            $booking->load('pelanggan');
            return redirect()->route('booking.status');
        }

        return back()->with('info', 'Pembayaran belum diterima. Silakan lakukan pembayaran dan klik "Saya Sudah Bayar".');
    }

    public function konfirmasiManual($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status === 'Berhasil') {
            $booking->load('pelanggan');
            session(['nomor_hp' => $booking->pelanggan->nomor_hp]);
            return redirect()->route('booking.status');
        }

        if ($booking->pembayaran()->exists()) {
            $booking->load('pelanggan');
            session(['nomor_hp' => $booking->pelanggan->nomor_hp]);
            return redirect()->route('booking.status');
        }

       try {
            $waGagal = $this->tandaiBerhasil($booking);
            $booking->load('pelanggan');
            session(['nomor_hp' => $booking->pelanggan->nomor_hp]);

            return redirect()->route('pembayaran.show', $booking->id)
                ->with('bayar_sukses', true)
                ->with('wa_gagal', $waGagal);
        } catch (Exception $e) {
            Log::error('Konfirmasi manual gagal', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memproses konfirmasi: ' . $e->getMessage());
        }
    }

    public function simulasi($bookingId)
    {
        return $this->konfirmasiManual($bookingId);
    }

    private function tandaiBerhasil(Booking $booking): bool
    {
        $waGagal = false;
        $pelangganId  = $booking->pelanggan_id;
        $kodeBooking  = $booking->kode_booking;
        $semuaBookingResult = null;

        DB::transaction(function () use ($booking, &$semuaBookingResult) {
            $booking = Booking::where('id', $booking->id)->lockForUpdate()->first();

            if ($booking->status === 'Berhasil' || $booking->pembayaran()->exists()) {
                return;
            }

            $semuaBooking = Booking::with(['jadwal.lapangan'])
                ->where('kode_booking', $booking->kode_booking)
                ->lockForUpdate()
                ->orderBy('jam_mulai')
                ->get();

            $totalKeseluruhan = $semuaBooking->sum('total_bayar');

            foreach ($semuaBooking as $b) {
                $b->update(['status' => 'Berhasil']);
                Jadwal::where('id', $b->jadwal_id)->update(['status_jadwal' => 'Penuh']);
            }

            Pembayaran::create([
                'booking_id'         => $booking->id,
                'metode_pembayaran'  => 'QRIS',
                'tanggal_pembayaran' => now()->toDateString(),
                'total_pembayaran'   => $totalKeseluruhan,
                'status_pembayaran'  => 'Berhasil',
            ]);

            $jamMulai   = date('H.i', strtotime($semuaBooking->first()->jam_mulai));
            $jamSelesai = date('H.i', strtotime($semuaBooking->last()->jam_selesai));

            $pesanNotif = "Booking berhasil! Kode: {$booking->kode_booking}. " .
                "Lapangan: {$booking->jadwal->lapangan->nama_lapangan}, " .
                "Tanggal: {$booking->jadwal->tanggal_jadwal}, " .
                "Jam: {$jamMulai}-{$jamSelesai}.";

            Notifikasi::create([
                'booking_id'      => $booking->id,
                'pesan'           => $pesanNotif,
                'tanggal_kirim'   => now(),
                'status_terkirim' => true,
            ]);

            $semuaBookingResult = $semuaBooking;
        });

      
        if ($semuaBookingResult) {
            try {
                $pelanggan    = \App\Models\Pelanggan::find($pelangganId);
                $firstBooking = $semuaBookingResult->first();
                $lastBooking  = $semuaBookingResult->last();
                $jamMulai     = date('H.i', strtotime($firstBooking->jam_mulai));
                $jamSelesai   = date('H.i', strtotime($lastBooking->jam_selesai));
                $totalKeseluruhan = $semuaBookingResult->sum('total_bayar');

                $pesan =
                    "⚽ *Bintang Sport Center*\n\n" .
                    "Halo *{$pelanggan->nama_pelanggan}*,\n\n" .
                    "Pembayaran Anda telah *berhasil* diterima dan booking telah dikonfirmasi. 🎉\n\n" .
                    "==================================\n" .
                    "📋 *DETAIL BOOKING*\n" .
                    "==================================\n" .
                    "🔖 No. Booking : *{$kodeBooking}*\n" .
                    "📅 Tanggal     : " . \Carbon\Carbon::parse($firstBooking->jadwal->tanggal_jadwal)->locale('id')->translatedFormat('l, d F Y') . "\n" .
                    "⏰ Jam         : {$jamMulai} - {$jamSelesai}\n" .
                    "🏟 Lapangan    : {$firstBooking->jadwal->lapangan->nama_lapangan}\n" .
                    "💰 Total Bayar : *Rp " . number_format($totalKeseluruhan, 0, ',', '.') . "*\n" .
                    "==================================\n\n" .
                    "✅ Status : *Terkonfirmasi*\n\n" .
                    "Mohon datang sesuai jadwal. Tunjukkan kode booking kepada petugas saat check-in.\n\n" .
                    "Terima kasih telah memilih *Bintang Sport Center* 🙏";

                Log::info('Mau kirim WA ke: ' . $pelanggan->nomor_hp);
                $hasil = app(WhatsappService::class)->kirim($pelanggan->nomor_hp, $pesan);
                Log::info('WA terkirim');

                if (isset($hasil['status']) && $hasil['status'] === false) {
                    $waGagal = true;
                }
            } catch (\Exception $e) {
                Log::error('Gagal kirim WA', ['error' => $e->getMessage()]);
                $waGagal = true;
            }
        }

        return $waGagal;
    }

    public function sukses($bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])
            ->findOrFail($bookingId);

        $booking->load('pelanggan');
        session(['nomor_hp' => $booking->pelanggan->nomor_hp]);
        return redirect()->route('booking.status');
    }
}