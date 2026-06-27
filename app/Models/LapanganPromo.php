<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LapanganPromo extends Model
{
    protected $table = 'lapangan_promo';
    protected $fillable = ['lapangan_id', 'promo_id'];
}