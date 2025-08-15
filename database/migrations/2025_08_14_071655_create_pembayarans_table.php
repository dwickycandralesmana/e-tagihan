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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('jenjang_id');
            $table->year('tahun_ajaran')->nullable();
            $table->string('kelas')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->enum('metode_pembayaran', ['Cash', 'Transfer'])->default('Cash');
            $table->decimal('total_tagihan', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('total_bayar', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('jenjang_id')->references('id')->on('jenjangs')->onDelete('cascade');

            $table->index('siswa_id');
            $table->index('jenjang_id');
            $table->index(['siswa_id', 'tahun_ajaran', 'jenjang_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
