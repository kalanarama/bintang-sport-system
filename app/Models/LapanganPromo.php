<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LapanganPromo extends Model
{
    protected $table = 'lapangan_promo';

    protected $fillable = [
        'lapangan_id',
        'promo_id',
        'slots',
    ];

    protected $casts = [
        'slots' => 'array',
    ];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id');
    }
}