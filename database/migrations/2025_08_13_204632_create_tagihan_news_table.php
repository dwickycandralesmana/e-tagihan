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
        Schema::create('tagihan_news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipe_tagihan_id');
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('jenjang_id');
            $table->year('tahun_ajaran')->nullable();
            $table->string('kelas')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->integer('total')->default(0);
            $table->timestamps();

            $table->foreign('tipe_tagihan_id')->references('id')->on('tipe_tagihans')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('jenjang_id')->references('id')->on('jenjangs')->onDelete('cascade');

            $table->index('tipe_tagihan_id');
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
        Schema::dropIfExists('tagihan_news');
    }
};
