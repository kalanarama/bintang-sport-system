<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisLapangan extends Model
{
    protected $table = 'jenis_lapangan';

    protected $fillable = [
        'nama_jenis_lapangan',
        'harga_per_jam'
    ];

    public function lapangans()
    {
        return $this->hasMany(Lapangan::class,'jenis_lapangan_id');
    }
}