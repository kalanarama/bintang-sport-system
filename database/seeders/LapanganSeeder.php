<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LapanganSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lapangan')->insert([
            [
                'nama_lapangan'     => 'Futsal A01',
                'jenis_lapangan_id' => 1,
                'jam_buka'          => '06:00',
                'jam_tutup'         => '22:00',
                'durasi_slot'       => 60,
                'foto_lapangan'     => 'img/lapangan/futsal-1.jpg',
                'status_lapangan'   => 'nonaktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'nama_lapangan'     => 'Futsal A02',
                'jenis_lapangan_id' => 1,
                'jam_buka'          => '06:00',
                'jam_tutup'         => '22:00',
                'durasi_slot'       => 60,
                'foto_lapangan'     => 'img/lapangan/futsal-1.jpg',
                'status_lapangan'   => 'aktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'nama_lapangan'     => 'Futsal B01',
                'jenis_lapangan_id' => 2,
                'jam_buka'          => '06:00',
                'jam_tutup'         => '22:00',
                'durasi_slot'       => 60,
                'foto_lapangan'     => 'img/lapangan/futsal-2.jpg',
                'status_lapangan'   => 'aktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'nama_lapangan'     => 'Badminton 01',
                'jenis_lapangan_id' => 3,
                'jam_buka'          => '06:00',
                'jam_tutup'         => '22:00',
                'durasi_slot'       => 60,
                'foto_lapangan'     => 'img/lapangan/badminton-1.jpg',
                'status_lapangan'   => 'aktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'nama_lapangan'     => 'Badminton 02',
                'jenis_lapangan_id' => 3,
                'jam_buka'          => '06:00',
                'jam_tutup'         => '22:00',
                'durasi_slot'       => 60,
                'foto_lapangan'     => 'img/lapangan/badminton-2.jpg',
                'status_lapangan'   => 'aktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'nama_lapangan'     => 'Badminton 03',
                'jenis_lapangan_id' => 3,
                'jam_buka'          => '06:00',
                'jam_tutup'         => '22:00',
                'durasi_slot'       => 60,
                'foto_lapangan'     => 'img/lapangan/badminton-3.jpg',
                'status_lapangan'   => 'aktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'nama_lapangan'     => 'Badminton 04',
                'jenis_lapangan_id' => 3,
                'jam_buka'          => '06:00',
                'jam_tutup'         => '22:00',
                'durasi_slot'       => 60,
                'foto_lapangan'     => 'img/lapangan/badminton-4.png',
                'status_lapangan'   => 'aktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'nama_lapangan'     => 'Basket 01',
                'jenis_lapangan_id' => 4,
                'jam_buka'          => '06:00',
                'jam_tutup'         => '22:00',
                'durasi_slot'       => 60,
                'foto_lapangan'     => 'img/lapangan/basket.jpg',
                'status_lapangan'   => 'nonaktif',
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}