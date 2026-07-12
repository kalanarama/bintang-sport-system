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
                'nama_lapangan'   => 'Futsal A01',
                'jenis_lapangan'  => 'Futsal A',
                'harga_lapangan'  => 120000,
                'jam_buka'        => '06:00',
                'jam_tutup'       => '22:00',
                'durasi_slot'     => 60,
                'foto_lapangan'   => 'img/lapangan/futsal-1.jpg',
                'status_lapangan' => 'nonaktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_lapangan'   => 'Futsal A02',
                'jenis_lapangan'  => 'Futsal A',
                'harga_lapangan'  => 120000,
                'jam_buka'        => '06:00',
                'jam_tutup'       => '22:00',
                'durasi_slot'     => 60,
                'foto_lapangan'   => 'img/lapangan/futsal-1.jpg',
                'status_lapangan' => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_lapangan'   => 'Futsal B01',
                'jenis_lapangan'  => 'Futsal B',
                'harga_lapangan'  => 150000,
                'jam_buka'        => '06:00',
                'jam_tutup'       => '22:00',
                'durasi_slot'     => 60,
                'foto_lapangan'   => 'img/lapangan/futsal-2.jpg',
                'status_lapangan' => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_lapangan'   => 'Badminton 01',
                'jenis_lapangan'  => 'Badminton',
                'harga_lapangan'  => 100000,
                'jam_buka'        => '06:00',
                'jam_tutup'       => '22:00',
                'durasi_slot'     => 60,
                'foto_lapangan'   => 'img/lapangan/badminton-1.jpg',
                'status_lapangan' => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_lapangan'   => 'Badminton 02',
                'jenis_lapangan'  => 'Badminton',
                'harga_lapangan'  => 200000,
                'jam_buka'        => '06:00',
                'jam_tutup'       => '22:00',
                'durasi_slot'     => 60,
                'foto_lapangan'   => 'img/lapangan/badminton-2.jpg',
                'status_lapangan' => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_lapangan'   => 'Badminton 03',
                'jenis_lapangan'  => 'Badminton',
                'harga_lapangan'  => 150000,
                'jam_buka'        => '06:00',
                'jam_tutup'       => '22:00',
                'durasi_slot'     => 60,
                'foto_lapangan'   => 'img/lapangan/badminton-3.jpg',
                'status_lapangan' => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_lapangan'   => 'Badminton 04',
                'jenis_lapangan'  => 'Badminton',
                'harga_lapangan'  => 200000,
                'jam_buka'        => '06:00',
                'jam_tutup'       => '22:00',
                'durasi_slot'     => 60,
                'foto_lapangan'   => 'img/lapangan/badminton-4.png',
                'status_lapangan' => 'aktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nama_lapangan'   => 'Basket 01',
                'jenis_lapangan'  => 'Basket',
                'harga_lapangan'  => 120000,
                'jam_buka'        => '06:00',
                'jam_tutup'       => '22:00',
                'durasi_slot'     => 60,
                'foto_lapangan'   => 'img/lapangan/basket.jpg',
                'status_lapangan' => 'nonaktif',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}