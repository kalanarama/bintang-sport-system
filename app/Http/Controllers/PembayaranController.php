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
    /**
     * Menampilkan halaman pembayaran dengan QRIS statis.
     */
    public function show($bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->findOrFail($bookingId);

        if ($booking->status !== 'Tertunda') {
            return redirect()->route('pembayaran.sukses', $bookingId);
        }

        return view('pelanggan.pembayaran.show', compact('booking'));
    }

    /**
     * Cek status pembayaran (hanya menampilkan status, tidak mengubah apapun).
     */
    public function proses(Request $request, $bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->findOrFail($bookingId);

        if ($booking->status === 'Berhasil') {
            return redirect()->route('pembayaran.sukses', $bookingId);
        }

        return back()->with('info', 'Pembayaran belum diterima. Silakan lakukan pembayaran dan klik "Saya Sudah Bayar".');
    }

    /**
     * Konfirmasi manual setelah pengguna membayar (tombol "Saya Sudah Bayar").
     */
    public function konfirmasiManual($bookingId)
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
                ->with('success', 'Pembayaran berhasil dikonfirmasi!');
        } catch (Exception $e) {
            Log::error('Konfirmasi manual gagal', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Gagal memproses konfirmasi: ' . $e->getMessage());
        }
    }

    /**
     * Method simulasi (opsional, untuk testing).
     */
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

    /**
     * Proses inti menandai booking berhasil.
     * Menggunakan lockForUpdate() untuk mencegah duplikasi notifikasi.
     * Hanya membuat Notifikasi di database, TIDAK mengirim WhatsApp (agar tidak duplikat).
     */
    private function tandaiBerhasil(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            // 🔒 Kunci baris booking agar tidak terjadi race condition
            $booking = Booking::where('id', $booking->id)->lockForUpdate()->first();

            // Cek ulang setelah di-lock
            if ($booking->status === 'Berhasil' || $booking->pembayaran()->exists()) {
                return;
            }

            // Catat pembayaran
            Pembayaran::create([
                'booking_id'         => $booking->id,
                'metode_pembayaran'  => 'QRIS',
                'tanggal_pembayaran' => now()->toDateString(),
                'total_pembayaran'   => $booking->total_bayar,
                'status_pembayaran'  => 'Berhasil',
            ]);

            // Update status booking
            $booking->update(['status' => 'Berhasil']);

            // Update status jadwal
            Jadwal::where('id', $booking->jadwal_id)->update(['status_jadwal' => 'Penuh']);

            // Buat notifikasi di database (tanpa WhatsApp)
            $pesanNotif = "Booking berhasil! Kode: {$booking->kode_booking}. Lapangan: {$booking->jadwal->lapangan->nama_lapangan}, Tanggal: {$booking->jadwal->tanggal_jadwal}, Jam: "
                . date('H.i', strtotime($booking->jam_mulai)) . "-" . date('H.i', strtotime($booking->jam_selesai)) . ".";

            Notifikasi::create([
                'booking_id'      => $booking->id,
                'pesan'           => $pesanNotif,
                'tanggal_kirim'   => now(),
                'status_terkirim' => true,
            ]);

            // ⛔ TIDAK MENGIRIM WHATSAPP LAGI (hanya notifikasi database)
            // Jika ingin mengirim WhatsApp, aktifkan kode di bawah ini, tapi akan duplikat
            // if ($booking->pelanggan && $booking->pelanggan->nomor_hp) {
            //     $pesanWa = "✅ *Bintang Sport Center*\n\nHalo *{$booking->pelanggan->nama_pelanggan}*,\nPembayaran Anda telah kami terima.\n\n🔖 No. Booking : *{$booking->kode_booking}*\n🏟 Lapangan : {$booking->jadwal->lapangan->nama_lapangan}\n💰 Total : Rp" . number_format($booking->total_bayar, 0, ',', '.') . "\n\nTerima kasih 🙏\n*Bintang Sport Center*";
            //     app(WhatsappService::class)->kirim($booking->pelanggan->nomor_hp, $pesanWa);
            // }
        });
    }

    /**
     * Halaman sukses setelah pembayaran berhasil.
     */
    public function sukses($bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])
            ->findOrFail($bookingId);

        return view('pelanggan.pembayaran.sukses', compact('booking'));
    }
}