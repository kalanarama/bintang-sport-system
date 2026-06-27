<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $fillable = ['lapangan_id', 'tanggal_jadwal', 'jam_mulai', 'jam_selesai', 'status_jadwal'];

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'jadwal_id');
    }
}