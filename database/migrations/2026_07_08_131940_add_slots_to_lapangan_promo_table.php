<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lapangan_promo', function (Blueprint $table) {
            $table->json('slots')->nullable()->after('promo_id');
        });
    }

    public function down()
    {
        Schema::table('lapangan_promo', function (Blueprint $table) {
            $table->dropColumn('slots');
        });
    }
};