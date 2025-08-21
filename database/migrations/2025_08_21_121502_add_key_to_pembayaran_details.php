<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pembayaran_details', function (Blueprint $table) {
            $table->string('key')->nullable();
            $table->unsignedBigInteger('history_kelas_id')->nullable();

            $table->foreign('history_kelas_id')->references('id')->on('history_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran_details', function (Blueprint $table) {
            $table->dropColumn('key');
            $table->dropColumn('history_kelas_id');
        });
    }
};
