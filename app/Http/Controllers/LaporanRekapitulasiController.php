<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanRekapitulasiController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal  ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->toDateString();

        $bookings = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where('status', 'Berhasil')
            ->latest()
            ->get();

        $totalBooking    = $bookings->count();
        $totalPendapatan = $bookings->sum('total_bayar');
        $totalPelanggan  = $bookings->pluck('pelanggan_id')->unique()->count();

        return view('admin.laporan.index', compact(
            'bookings', 'totalBooking', 'totalPendapatan',
            'totalPelanggan', 'tanggalAwal', 'tanggalAkhir'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal  ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->toDateString();

        $bookings = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])
            ->whereBetween('created_at', [$tanggalAwal, $tanggalAkhir])
            ->where('status', 'Berhasil')
            ->latest()
            ->get();

        $totalBooking    = $bookings->count();
        $totalPendapatan = $bookings->sum('total_bayar');
        $totalPelanggan  = $bookings->pluck('pelanggan_id')->unique()->count();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'bookings', 'totalBooking', 'totalPendapatan',
            'totalPelanggan', 'tanggalAwal', 'tanggalAkhir'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-rekapitulasi-' . $tanggalAwal . '-sd-' . $tanggalAkhir . '.pdf');
    }
}