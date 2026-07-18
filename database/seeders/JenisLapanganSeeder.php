<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLapanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_lapangan')->insert([
        [
        'nama_jenis_lapangan'=> 'Futsal A',
        'harga_per_jam'      => 70000,
        'created_at'         => now(),
        'updated_at'         => now(),
        ],
        [
        'nama_jenis_lapangan'=> 'Futsal B',
        'harga_per_jam'      => 80000,
        'created_at'         => now(),
        'updated_at'         => now(),        
        ],
        [
        'nama_jenis_lapangan'=> 'Badminton',
        'harga_per_jam'      => 60000,
        'created_at'         => now(),
        'updated_at'         => now(),        
        ],
        [
        'nama_jenis_lapangan'=> 'Basket',
        'harga_per_jam'      => 80000,
        'created_at'         => now(),
        'updated_at'         => now(),
        ]
        ]);
    }
}
