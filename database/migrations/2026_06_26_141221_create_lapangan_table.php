<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lapangan');
            $table->foreignId('jenis_lapangan_id')
                ->constrained('jenis_lapangan')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->time('jam_buka');
            $table->time('jam_tutup');
            $table->unsignedTinyInteger('durasi_slot');
            $table->string('foto_lapangan')->nullable();
            $table->enum('status_lapangan', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapangan');
    }
};