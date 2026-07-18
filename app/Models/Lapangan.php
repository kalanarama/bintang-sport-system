<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $table = 'lapangan';

    protected $fillable = [
        'nama_lapangan',
        'jenis_lapangan_id',
        'jam_buka',
        'jam_tutup',
        'durasi_slot',
        'foto_lapangan',
        'status_lapangan',
    ];

    // Relasi ke jenis lapangan
    public function jenisLapangan()
    {
        return $this->belongsTo(JenisLapangan::class, 'jenis_lapangan_id');
    }

    // Relasi ke jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'lapangan_id');
    }

    // Relasi ke promo
    public function promos()
    {
        return $this->belongsToMany(
            Promo::class,
            'lapangan_promo',
            'lapangan_id',
            'promo_id'
        )->withPivot('slots')
         ->withTimestamps();
    }
}