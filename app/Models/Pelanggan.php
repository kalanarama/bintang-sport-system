<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $fillable = ['nama_pelanggan', 'nomor_hp'];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'pelanggan_id');
    }
}