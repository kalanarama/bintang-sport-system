<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        // Data jadwal untuk lapangan dengan ID 1–8 (sesuai LapanganSeeder)
        // Tanggal 16–18 Juli 2026 (pastikan masih >= hari ini)
        $jadwals = [
            // ====================
            // Lapangan 1 (Futsal A01) - pastikan status 'aktif' di LapanganSeeder
            // ====================
            ['lapangan_id' => 1, 'tanggal_jadwal' => '2026-07-16', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:00:00'],
            ['lapangan_id' => 1, 'tanggal_jadwal' => '2026-07-16', 'jam_mulai' => '09:00:00', 'jam_selesai' => '10:00:00'],
            ['lapangan_id' => 1, 'tanggal_jadwal' => '2026-07-17', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:00:00'],
            ['lapangan_id' => 1, 'tanggal_jadwal' => '2026-07-17', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:00:00'],
            ['lapangan_id' => 1, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '14:00:00', 'jam_selesai' => '15:00:00'],
            ['lapangan_id' => 1, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '15:00:00', 'jam_selesai' => '16:00:00'],

            // ====================
            // Lapangan 2 (Futsal A02)
            // ====================
            ['lapangan_id' => 2, 'tanggal_jadwal' => '2026-07-16', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:00:00'],
            ['lapangan_id' => 2, 'tanggal_jadwal' => '2026-07-17', 'jam_mulai' => '09:00:00', 'jam_selesai' => '10:00:00'],
            ['lapangan_id' => 2, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:00:00'],
            ['lapangan_id' => 2, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '09:00:00', 'jam_selesai' => '10:00:00'],

            // ====================
            // Lapangan 3 (Futsal B01)
            // ====================
            ['lapangan_id' => 3, 'tanggal_jadwal' => '2026-07-16', 'jam_mulai' => '11:00:00', 'jam_selesai' => '12:00:00'],
            ['lapangan_id' => 3, 'tanggal_jadwal' => '2026-07-17', 'jam_mulai' => '14:00:00', 'jam_selesai' => '15:00:00'],
            ['lapangan_id' => 3, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:00:00'],
            ['lapangan_id' => 3, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '11:00:00', 'jam_selesai' => '12:00:00'],

            // ====================
            // Lapangan 4 (Badminton 01)
            // ====================
            ['lapangan_id' => 4, 'tanggal_jadwal' => '2026-07-16', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:00:00'],
            ['lapangan_id' => 4, 'tanggal_jadwal' => '2026-07-17', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:00:00'],
            ['lapangan_id' => 4, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '15:00:00', 'jam_selesai' => '16:00:00'],

            // ====================
            // Lapangan 5 (Badminton 02)
            // ====================
            ['lapangan_id' => 5, 'tanggal_jadwal' => '2026-07-16', 'jam_mulai' => '09:00:00', 'jam_selesai' => '10:00:00'],
            ['lapangan_id' => 5, 'tanggal_jadwal' => '2026-07-17', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:00:00'],
            ['lapangan_id' => 5, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '14:00:00', 'jam_selesai' => '15:00:00'],

            // ====================
            // Lapangan 6 (Badminton 03)
            // ====================
            ['lapangan_id' => 6, 'tanggal_jadwal' => '2026-07-16', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:00:00'],
            ['lapangan_id' => 6, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '08:00:00', 'jam_selesai' => '09:00:00'],
            ['lapangan_id' => 6, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '09:00:00', 'jam_selesai' => '10:00:00'],

            // ====================
            // Lapangan 7 (Badminton 04)
            // ====================
            ['lapangan_id' => 7, 'tanggal_jadwal' => '2026-07-17', 'jam_mulai' => '11:00:00', 'jam_selesai' => '12:00:00'],
            ['lapangan_id' => 7, 'tanggal_jadwal' => '2026-07-18', 'jam_mulai' => '10:00:00', 'jam_selesai' => '11:00:00'],

            // ====================
            // Lapangan 8 (Basket 01) - meskipun nonaktif, tetap buat kalau mau
            // ====================
            ['lapangan_id' => 8, 'tanggal_jadwal' => '2026-07-16', 'jam_mulai' => '14:00:00', 'jam_selesai' => '15:00:00'],
            ['lapangan_id' => 8, 'tanggal_jadwal' => '2026-07-17', 'jam_mulai' => '15:00:00', 'jam_selesai' => '16:00:00'],
        ];

        foreach ($jadwals as $item) {
            DB::table('jadwal')->insert([
                'lapangan_id'    => $item['lapangan_id'],
                'tanggal_jadwal' => $item['tanggal_jadwal'],
                'jam_mulai'      => $item['jam_mulai'],
                'jam_selesai'    => $item['jam_selesai'],
                'status_jadwal'  => 'Tersedia',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}