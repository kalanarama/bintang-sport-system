<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'booking';
    protected $fillable = [
        'jadwal_id', 'pelanggan_id',
        'jam_mulai', 'jam_selesai', 'total_bayar',
        'status', 'kode_booking', 'qris_string', 'qris_request_id', 'qris_expired_at',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'booking_id');
    }

    public function notifikasi()
    {
        return $this->hasOne(Notifikasi::class, 'booking_id');
    }
}