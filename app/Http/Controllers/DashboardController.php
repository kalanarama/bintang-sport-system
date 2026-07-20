<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Promo;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $bookingHariIni = Booking::whereDate('created_at', $today)->count();


        $pendapatanHariIni = Booking::whereDate('created_at', $today)
            ->where('status', 'Berhasil')
            ->sum('total_bayar');

        $lapanganAktif = Lapangan::where('status_lapangan', 'aktif')->count();

        $promoAktif = Promo::where('status_promo', true)
            ->where('tanggal_mulai', '<=', $today)
            ->where('tanggal_berakhir', '>=', $today)
            ->count();

        // Statistik 7 hari terakhir
        $statsMingguan = collect(range(6, 0))->map(function($i) {
            $date = now()->subDays($i)->toDateString();
            return [
                'tanggal' => now()->subDays($i)->format('D'),
                'total'   => Booking::whereDate('created_at', $date)->count(),
            ];
        });
        // Status booking hari ini
        $statusHariIni = [
            'berhasil'   => Booking::whereDate('created_at', $today)->where('status', 'Berhasil')->count(),
            'tertunda'   => Booking::whereDate('created_at', $today)->where('status', 'Tertunda')->count(),
            'dibatalkan' => Booking::whereDate('created_at', $today)->where('status', 'Dibatalkan')->count(),
        ];

        // Booking terbaru
        $bookingTerbaru = Booking::with(['jadwal.lapangan', 'pelanggan'])
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', compact(
            'bookingHariIni',
            'pendapatanHariIni',
            'lapanganAktif',
            'promoAktif',
            'statsMingguan',
            'statusHariIni',
            'bookingTerbaru'
        ));
    }
}