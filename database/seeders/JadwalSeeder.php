<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lapangan;
use App\Http\Controllers\LapanganController;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $controller = new LapanganController();
        $lapangans = Lapangan::where('status_lapangan', 'aktif')->get();
        foreach ($lapangans as $lapangan) {
            $controller->generateJadwal($lapangan);
        }
    }
}