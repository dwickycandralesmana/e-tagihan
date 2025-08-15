<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TagihanNew;
use App\Models\TipeTagihan;
use Illuminate\Console\Command;

class GenerateTagihan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-tagihan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tahunAjaran = 2025;
        $siswaX      = Siswa::limit(10)->offset(0)->get();
        $siswaXI     = Siswa::limit(10)->offset(10)->get();
        $siswaXII    = Siswa::limit(10)->offset(20)->get();

        foreach ($siswaX as $siswa) {
            $tipeTagihan = TipeTagihan::where('tahun_ajaran', $tahunAjaran)->where('jenjang_id', 1)->get();

            foreach ($tipeTagihan as $tipe) {
                $tagihan                  = new TagihanNew();
                $tagihan->siswa_id        = $siswa->id;
                $tagihan->tipe_tagihan_id = $tipe->id;
                $tagihan->jenjang_id      = $tipe->jenjang_id;
                $tagihan->tahun_ajaran    = $tahunAjaran;
                $tagihan->save();
            }
        }

        foreach ($siswaXI as $siswa) {
            $tipeTagihan = TipeTagihan::where('tahun_ajaran', $tahunAjaran)->where('jenjang_id', 2)->get();

            foreach ($tipeTagihan as $tipe) {
                $tagihan                  = new TagihanNew();
                $tagihan->siswa_id        = $siswa->id;
                $tagihan->tipe_tagihan_id = $tipe->id;
                $tagihan->jenjang_id      = $tipe->jenjang_id;
                $tagihan->tahun_ajaran    = $tahunAjaran;
                $tagihan->save();
            }
        }

        foreach ($siswaXII as $siswa) {
            $tipeTagihan = TipeTagihan::where('tahun_ajaran', $tahunAjaran)->where('jenjang_id', 3)->get();

            foreach ($tipeTagihan as $tipe) {
                $tagihan                  = new TagihanNew();
                $tagihan->siswa_id        = $siswa->id;
                $tagihan->tipe_tagihan_id = $tipe->id;
                $tagihan->jenjang_id      = $tipe->jenjang_id;
                $tagihan->tahun_ajaran    = $tahunAjaran;
                $tagihan->save();
            }
        }
    }
}
