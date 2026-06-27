<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanRekapitulasi extends Model
{
    protected $table = 'laporan_rekapitulasi';
    protected $fillable = [
        'tanggal_awal', 'tanggal_akhir',
        'total_booking', 'total_pendapatan', 'total_pelanggan'
    ];
}