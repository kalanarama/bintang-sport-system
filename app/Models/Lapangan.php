<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $table = 'lapangan';
    protected $fillable = [
        'nama_lapangan',
        'jenis_lapangan',
        'harga_lapangan',
        'jam_buka',
        'jam_tutup',
        'durasi_slot',
        'foto_lapangan',
        'status_lapangan',
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'lapangan_id');
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'lapangan_promo', 'lapangan_id', 'promo_id')
                    ->withPivot('slots')
                    ->withTimestamps();
    }
}