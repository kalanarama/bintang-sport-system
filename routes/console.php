<?php

use App\Models\Jadwal;
use App\Models\Lapangan;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Generate jadwal awal 6 bulan ke depan (jalankan sekali)
Artisan::command('jadwal:generate', function () {
    $lapangans = Lapangan::where('status_lapangan', 'aktif')->get();
    $this->info("Ditemukan {$lapangans->count()} lapangan aktif.");

    foreach ($lapangans as $lapangan) {
        $this->info("Generating: {$lapangan->nama_lapangan}...");

        $tanggalMulai = Carbon::today();
        $tanggalAkhir = Carbon::today()->addMonths(6);
        $durasi       = (int) $lapangan->durasi_slot;
        $jamBuka      = Carbon::parse($lapangan->jam_buka);
        $jamTutup     = Carbon::parse($lapangan->jam_tutup);

        for ($date = $tanggalMulai->copy(); $date->lte($tanggalAkhir); $date->addDay()) {
            $jamMulai = $jamBuka->copy();

            while ($jamMulai->copy()->addMinutes($durasi)->lte($jamTutup)) {
                $jamSelesai = $jamMulai->copy()->addMinutes($durasi);

                $exists = Jadwal::where('lapangan_id', $lapangan->id)
                    ->where('tanggal_jadwal', $date->toDateString())
                    ->where('jam_mulai', $jamMulai->format('H:i'))
                    ->exists();

                if (!$exists) {
                    Jadwal::create([
                        'lapangan_id'    => $lapangan->id,
                        'tanggal_jadwal' => $date->toDateString(),
                        'jam_mulai'      => $jamMulai->format('H:i'),
                        'jam_selesai'    => $jamSelesai->format('H:i'),
                        'status_jadwal'  => 'Tersedia',
                    ]);
                }

                $jamMulai->addMinutes($durasi);
            }
        }

        $this->info("Selesai: {$lapangan->nama_lapangan}");
    }

    $this->info('Total: ' . Jadwal::count() . ' jadwal');
})->purpose('Generate jadwal 6 bulan ke depan untuk semua lapangan aktif');

// Rolling harian — tambah 1 hari baru tiap hari jam 00:01
Schedule::call(function () {
    $targetDate = Carbon::today()->addMonths(6);
    $lapangans  = Lapangan::where('status_lapangan', 'aktif')->get();

    foreach ($lapangans as $lapangan) {
        $durasi   = (int) $lapangan->durasi_slot;
        $jamBuka  = Carbon::parse($lapangan->jam_buka);
        $jamTutup = Carbon::parse($lapangan->jam_tutup);
        $jamMulai = $jamBuka->copy();

        while ($jamMulai->copy()->addMinutes($durasi)->lte($jamTutup)) {
            $jamSelesai = $jamMulai->copy()->addMinutes($durasi);

            $exists = Jadwal::where('lapangan_id', $lapangan->id)
                ->where('tanggal_jadwal', $targetDate->toDateString())
                ->where('jam_mulai', $jamMulai->format('H:i'))
                ->exists();

            if (!$exists) {
                Jadwal::create([
                    'lapangan_id'    => $lapangan->id,
                    'tanggal_jadwal' => $targetDate->toDateString(),
                    'jam_mulai'      => $jamMulai->format('H:i'),
                    'jam_selesai'    => $jamSelesai->format('H:i'),
                    'status_jadwal'  => 'Tersedia',
                ]);
            }

            $jamMulai->addMinutes($durasi);
        }
    }
})->dailyAt('00:01')->name('generate-jadwal-harian');