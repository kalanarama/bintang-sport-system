<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasis = Notifikasi::with('booking.pelanggan')
            ->latest()
            ->get();

        return view('admin.notifikasi.index', compact('notifikasis'));
    }
}