<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;
use App\Models\LapanganPromo;
use Carbon\Carbon;

class PromoSeeder extends Seeder
{
    public function run(): void
    {
        $promos = [
            // =====================
            // DATA AKTIF (sekarang)
            // =====================
            [
                'nama_promo'       => 'Promo HUT RI',
                'diskon_persen'    => 20,
                'tanggal_mulai'    => Carbon::now()->subDays(5),
                'tanggal_berakhir' => Carbon::now()->addDays(10),
                'status_promo'     => true,
                'lapangan_ids'     => [1, 2],
                'slots_per_lapangan' => [
                    1 => ['08:00-09:00', '09:00-10:00', '10:00-11:00'],
                    2 => ['08:00-09:00', '09:00-10:00'],
                ],
            ],
            [
                'nama_promo'       => 'Weekend Special',
                'diskon_persen'    => 15,
                'tanggal_mulai'    => Carbon::now()->subDays(2),
                'tanggal_berakhir' => Carbon::now()->addDays(5),
                'status_promo'     => true,
                'lapangan_ids'     => [3],
                'slots_per_lapangan' => [
                    3 => ['10:00-11:00', '11:00-12:00', '12:00-13:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Pelajar',
                'diskon_persen'    => 25,
                'tanggal_mulai'    => Carbon::now()->subDays(3),
                'tanggal_berakhir' => Carbon::now()->addDays(7),
                'status_promo'     => true,
                'lapangan_ids'     => [1, 3, 4],
                'slots_per_lapangan' => [
                    1 => ['14:00-15:00', '15:00-16:00', '16:00-17:00'],
                    3 => ['14:00-15:00', '15:00-16:00'],
                    4 => ['14:00-15:00', '15:00-16:00', '16:00-17:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Ramadan',
                'diskon_persen'    => 18,
                'tanggal_mulai'    => Carbon::now()->subDays(1),
                'tanggal_berakhir' => Carbon::now()->addDays(14),
                'status_promo'     => true,
                'lapangan_ids'     => [2, 5],
                'slots_per_lapangan' => [
                    2 => ['17:00-18:00', '18:00-19:00', '19:00-20:00'],
                    5 => ['17:00-18:00', '18:00-19:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Member Baru',
                'diskon_persen'    => 12,
                'tanggal_mulai'    => Carbon::now(),
                'tanggal_berakhir' => Carbon::now()->addDays(30),
                'status_promo'     => true,
                'lapangan_ids'     => [1, 2, 3],
                'slots_per_lapangan' => [
                    1 => ['08:00-09:00', '09:00-10:00'],
                    2 => ['08:00-09:00', '09:00-10:00'],
                    3 => ['08:00-09:00', '09:00-10:00'],
                ],
            ],

            // =====================
            // DATA HISTORIS (expired)
            // =====================
            [
                'nama_promo'       => 'Early Bird',
                'diskon_persen'    => 10,
                'tanggal_mulai'    => Carbon::now()->subDays(30),
                'tanggal_berakhir' => Carbon::now()->subDays(1),
                'status_promo'     => false,
                'lapangan_ids'     => [2, 4],
                'slots_per_lapangan' => [
                    2 => ['08:00-09:00', '09:00-10:00'],
                    4 => ['08:00-09:00', '09:00-10:00'],
                ],
            ],
            [
                'nama_promo'       => 'Flash Sale',
                'diskon_persen'    => 30,
                'tanggal_mulai'    => Carbon::now()->subDays(60),
                'tanggal_berakhir' => Carbon::now()->subDays(30),
                'status_promo'     => false,
                'lapangan_ids'     => [1],
                'slots_per_lapangan' => [
                    1 => ['08:00-09:00', '09:00-10:00', '10:00-11:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Mei Ceria',
                'diskon_persen'    => 22,
                'tanggal_mulai'    => Carbon::create(2026, 5, 1),
                'tanggal_berakhir' => Carbon::create(2026, 5, 31),
                'status_promo'     => false,
                'lapangan_ids'     => [1, 3],
                'slots_per_lapangan' => [
                    1 => ['10:00-11:00', '11:00-12:00', '12:00-13:00'],
                    3 => ['10:00-11:00', '11:00-12:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Hari Buruh',
                'diskon_persen'    => 15,
                'tanggal_mulai'    => Carbon::create(2026, 5, 1),
                'tanggal_berakhir' => Carbon::create(2026, 5, 3),
                'status_promo'     => false,
                'lapangan_ids'     => [2, 4],
                'slots_per_lapangan' => [
                    2 => ['08:00-09:00', '09:00-10:00', '10:00-11:00'],
                    4 => ['08:00-09:00', '09:00-10:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Libur Sekolah',
                'diskon_persen'    => 20,
                'tanggal_mulai'    => Carbon::create(2026, 6, 1),
                'tanggal_berakhir' => Carbon::create(2026, 6, 15),
                'status_promo'     => false,
                'lapangan_ids'     => [1, 2, 3],
                'slots_per_lapangan' => [
                    1 => ['13:00-14:00', '14:00-15:00', '15:00-16:00'],
                    2 => ['13:00-14:00', '14:00-15:00'],
                    3 => ['13:00-14:00', '14:00-15:00', '15:00-16:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Juni Spesial',
                'diskon_persen'    => 18,
                'tanggal_mulai'    => Carbon::create(2026, 6, 15),
                'tanggal_berakhir' => Carbon::create(2026, 6, 30),
                'status_promo'     => false,
                'lapangan_ids'     => [3, 4],
                'slots_per_lapangan' => [
                    3 => ['16:00-17:00', '17:00-18:00', '18:00-19:00'],
                    4 => ['16:00-17:00', '17:00-18:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Anniversary',
                'diskon_persen'    => 35,
                'tanggal_mulai'    => Carbon::create(2026, 4, 1),
                'tanggal_berakhir' => Carbon::create(2026, 4, 30),
                'status_promo'     => false,
                'lapangan_ids'     => [1, 2, 3, 4],
                'slots_per_lapangan' => [
                    1 => ['08:00-09:00', '09:00-10:00', '10:00-11:00'],
                    2 => ['08:00-09:00', '09:00-10:00'],
                    3 => ['08:00-09:00', '09:00-10:00', '10:00-11:00'],
                    4 => ['08:00-09:00', '09:00-10:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Imlek',
                'diskon_persen'    => 28,
                'tanggal_mulai'    => Carbon::create(2026, 1, 28),
                'tanggal_berakhir' => Carbon::create(2026, 2, 5),
                'status_promo'     => false,
                'lapangan_ids'     => [1, 3],
                'slots_per_lapangan' => [
                    1 => ['10:00-11:00', '11:00-12:00'],
                    3 => ['10:00-11:00', '11:00-12:00', '12:00-13:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Tahun Baru',
                'diskon_persen'    => 40,
                'tanggal_mulai'    => Carbon::create(2026, 1, 1),
                'tanggal_berakhir' => Carbon::create(2026, 1, 7),
                'status_promo'     => false,
                'lapangan_ids'     => [1, 2, 3, 4],
                'slots_per_lapangan' => [
                    1 => ['20:00-21:00', '21:00-22:00'],
                    2 => ['20:00-21:00', '21:00-22:00'],
                    3 => ['20:00-21:00', '21:00-22:00'],
                    4 => ['20:00-21:00', '21:00-22:00'],
                ],
            ],
            [
                'nama_promo'       => 'Promo Valentine',
                'diskon_persen'    => 14,
                'tanggal_mulai'    => Carbon::create(2026, 2, 14),
                'tanggal_berakhir' => Carbon::create(2026, 2, 16),
                'status_promo'     => false,
                'lapangan_ids'     => [2, 3],
                'slots_per_lapangan' => [
                    2 => ['17:00-18:00', '18:00-19:00', '19:00-20:00'],
                    3 => ['17:00-18:00', '18:00-19:00'],
                ],
            ],
        ];

        foreach ($promos as $data) {
            $lapanganIds      = $data['lapangan_ids'];
            $slotsPerLapangan = $data['slots_per_lapangan'];
            unset($data['lapangan_ids'], $data['slots_per_lapangan']);

            $promo = Promo::create($data);

            foreach ($lapanganIds as $lapanganId) {
                LapanganPromo::create([
                    'promo_id'    => $promo->id,
                    'lapangan_id' => $lapanganId,
                    'slots'       => $slotsPerLapangan[$lapanganId] ?? [],
                ]);
            }
        }
    }
}