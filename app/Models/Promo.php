<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $table = 'promo';
    protected $fillable = ['nama_promo', 'diskon_persen', 'tanggal_mulai', 'tanggal_berakhir', 'status_promo'];

    public function lapangans()
    {
        return $this->belongsToMany(Lapangan::class, 'lapangan_promo', 'promo_id', 'lapangan_id');
    }
}