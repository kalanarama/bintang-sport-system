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
            $table->string('metode_pembayaran')->default('QRIS');
            $table->date('tanggal_pembayaran')->nullable();
            $table->decimal('total_pembayaran', 10, 2); 
            $table->enum('status_pembayaran', ['pending', 'lunas', 'gagal'])->default('pending'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};