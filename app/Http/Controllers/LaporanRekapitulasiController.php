<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanRekapitulasiController extends Controller
{
    // Function untuk mengambil data booking
    private function getBookings($tanggalAwal, $tanggalAkhir)
    {
        return Booking::with([
            'jadwal.lapangan',
            'pelanggan',
            'pembayaran'
        ])
        ->whereDate('created_at', '>=', $tanggalAwal)
        ->whereDate('created_at', '<=', $tanggalAkhir)
        ->latest()
        ->get();
    }

    public function index(Request $request)
    {
       $tanggalAwal  = $request->tanggal_awal ?? now()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->addDay()->toDateString();
        $perPage      = $request->get('per_page', 10);

        if ($tanggalAwal && $tanggalAkhir) {
            $allBookings = $this->getBookings($tanggalAwal, $tanggalAkhir);
            $totalBooking    = $allBookings->where('status', 'Berhasil')->count();
            $totalPendapatan = $allBookings->where('status', 'Berhasil')->sum('total_bayar');
            $totalPelanggan  = $allBookings->pluck('pelanggan_id')->unique()->count();

            $bookings = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])
                ->whereDate('created_at', '>=', $tanggalAwal)
                ->whereDate('created_at', '<=', $tanggalAkhir)
                ->latest()
                ->paginate($perPage)
                ->appends($request->query());
        } else {
            $totalBooking    = 0;
            $totalPendapatan = 0;
            $totalPelanggan  = 0;
            $bookings = Booking::with(['jadwal.lapangan', 'pelanggan', 'pembayaran'])
                ->whereRaw('1=0')
                ->paginate($perPage);
        }

        return view('admin.laporan.index', compact(
            'bookings',
            'totalBooking',
            'totalPendapatan',
            'totalPelanggan',
            'tanggalAwal',
            'tanggalAkhir'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal ?? now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->toDateString();

        $bookings = $this->getBookings($tanggalAwal, $tanggalAkhir);

        $totalBooking = $bookings->where('status', 'Berhasil')->count();
        $totalPendapatan = $bookings->where('status', 'Berhasil')->sum('total_bayar');
        $totalPelanggan = $bookings->pluck('pelanggan_id')->unique()->count();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'bookings',
            'totalBooking',
            'totalPendapatan',
            'totalPelanggan',
            'tanggalAwal',
            'tanggalAkhir'
        ))->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-rekapitulasi-' . $tanggalAwal . '-sd-' . $tanggalAkhir . '.pdf'
        );
    }
}