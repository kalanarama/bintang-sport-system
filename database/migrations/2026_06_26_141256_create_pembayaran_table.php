<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking')->onDelete('cascade');
            $table->enum('metode_pembayaran', ['QRIS']);
            $table->date('tanggal_pembayaran')->nullable();
            $table->decimal('total_pembayaran', 10, 2);
            $table->enum('status_pembayaran', ['Tertunda', 'Berhasil', 'Dibatalkan'])->default('Tertunda');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};