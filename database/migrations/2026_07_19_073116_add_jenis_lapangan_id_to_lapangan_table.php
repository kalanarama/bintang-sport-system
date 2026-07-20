<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (!Schema::hasColumn('lapangan', 'jenis_lapangan_id')) {
                $table->foreignId('jenis_lapangan_id')
                    ->nullable()
                    ->after('nama_lapangan')
                    ->constrained('jenis_lapangan')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            $table->dropForeign(['jenis_lapangan_id']);
            $table->dropColumn('jenis_lapangan_id');
        });
    }
};