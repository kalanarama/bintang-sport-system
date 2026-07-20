<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promo extends Model
{
    protected $table = 'promo';

    protected $fillable = [
        'nama_promo',
        'diskon_persen',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status_promo',
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_berakhir' => 'date',
        'status_promo'     => 'boolean',
    ];

    public function lapangans()
    {
        return $this->belongsToMany(Lapangan::class, 'lapangan_promo', 'promo_id', 'lapangan_id')
                    ->withPivot('slots')
                    ->withTimestamps();
    }

    public function isAktif()
    {
         return $this->status_promo;
    }

    public function berakhirMingguIni()
    {
        return $this->status_promo
            && $this->tanggal_berakhir->between(now(), now()->endOfWeek());
    }

    public function hargaSetelahDiskon($harga)
    {
        return $harga - ($harga * $this->diskon_persen / 100);
    }
}