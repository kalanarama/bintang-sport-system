<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Notifikasi;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function show($bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->findOrFail($bookingId);

        if ($booking->status !== 'pending') {
            return redirect()->route('pembayaran.sukses', $bookingId);
        }

        return view('pelanggan.pembayaran.show', compact('booking'));
    }

    public function proses(Request $request, $bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->findOrFail($bookingId);

        if ($booking->status !== 'pending') {
            return redirect()->route('pembayaran.sukses', $bookingId);
        }

        Pembayaran::create([
            'booking_id'         => $booking->id,
            'metode_pembayaran'  => 'QRIS',
            'tanggal_pembayaran' => now()->toDateString(),
            'total_pembayaran'   => $booking->total_bayar,
            'status_pembayaran'  => 'lunas',
        ]);

        $booking->update(['status' => 'booked']);

        
        Jadwal::where('id', $booking->jadwal_id)->update(['status_jadwal' => 'booked']);

        Notifikasi::create([
            'booking_id'      => $booking->id,
            'pesan'           => "Booking berhasil! Kode: {$booking->kode_booking}. Lapangan: {$booking->jadwal->lapangan->nama_lapangan}, Tanggal: {$booking->jadwal->tanggal_jadwal}, Jam: {$booking->jam_mulai} - {$booking->jam_selesai}.",
            'tanggal_kirim'   => now(),
            'status_terkirim' => true,
        ]);

        return redirect()->route('pembayaran.sukses', $booking->id);
    }

    public function sukses($bookingId)
    {
        $booking = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])
            ->findOrFail($bookingId);

        return view('pelanggan.pembayaran.sukses', compact('booking'));
    }
}