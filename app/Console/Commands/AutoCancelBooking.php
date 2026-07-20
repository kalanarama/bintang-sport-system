<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Jadwal;
use Illuminate\Console\Command;

class AutoCancelBooking extends Command
{
    protected $signature   = 'booking:auto-cancel';
    protected $description = 'Auto cancel booking yang belum dibayar lebih dari 24 jam';

    public function handle()
    {
        $bookings = Booking::where('status', 'Tertunda')
            ->where('created_at', '<=', now()->subHours(24))
            ->get();

        foreach ($bookings as $booking) {
            $booking->update(['status' => 'Dibatalkan']);
            Jadwal::where('id', $booking->jadwal_id)
                ->update(['status_jadwal' => 'Tersedia']);
        }

        $this->info("Auto-cancel: {$bookings->count()} booking dibatalkan.");
    }
}