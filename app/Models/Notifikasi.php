<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $fillable = ['booking_id', 'pesan', 'tanggal_kirim', 'status_terkirim'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}