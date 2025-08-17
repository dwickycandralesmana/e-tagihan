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
        Schema::create('pembayaran_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembayaran_id');
            $table->unsignedBigInteger('tagihan_new_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('jenjang_id');
            $table->year('tahun_ajaran')->nullable();
            $table->integer('bulan')->nullable();
            $table->string('kelas')->nullable();
            $table->integer('bayar')->default(0);
            $table->integer('potongan')->default(0);
            $table->timestamps();

            $table->foreign('pembayaran_id')->references('id')->on('pembayarans')->onDelete('cascade');
            $table->foreign('tagihan_new_id')->references('id')->on('tagihan_news')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('jenjang_id')->references('id')->on('jenjangs')->onDelete('cascade');

            $table->index('pembayaran_id');
            $table->index(['tagihan_new_id', 'siswa_id', 'tahun_ajaran', 'jenjang_id'], 'idx_pembayaran_tagihan');
            $table->index('siswa_id');
            $table->index(['siswa_id', 'tahun_ajaran', 'bulan', 'jenjang_id'], 'idx_pembayaran_siswa_tahun_ajaran_bulan_jenjang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_details');
    }
};
