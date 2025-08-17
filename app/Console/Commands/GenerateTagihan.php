<?php

namespace App\Console\Commands;

use App\Models\HistoryKelas;
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
        // $tahunAjaran = 2025;
        $siswaX      = Siswa::limit(10)->offset(0)->get();
        $siswaXI     = Siswa::limit(10)->offset(10)->get();
        $siswaXII    = Siswa::limit(10)->offset(20)->get();

        $jenjangId = 1;
        $kelas = ["X", "XI", "XII"];
        for ($tahunAjaran = 2023; $tahunAjaran <= 2025; $tahunAjaran++) {
            foreach ($siswaX as $siswa) {
                $historyKelas               = new HistoryKelas();
                $historyKelas->siswa_id     = $siswa->id;
                $historyKelas->jenjang_id   = $jenjangId;
                $historyKelas->tahun_ajaran = $tahunAjaran;
                $historyKelas->kelas        = $kelas[$jenjangId - 1];
                $historyKelas->save();

                $tipeTagihan = TipeTagihan::where('tahun_ajaran', $tahunAjaran)->where('jenjang_id', $jenjangId)->get();

                foreach ($tipeTagihan as $tipe) {
                    $tagihan                   = new TagihanNew();
                    $tagihan->siswa_id         = $siswa->id;
                    $tagihan->history_kelas_id = $historyKelas->id;
                    $tagihan->tipe_tagihan_id  = $tipe->id;
                    $tagihan->jenjang_id       = $tipe->jenjang_id;
                    $tagihan->tahun_ajaran     = $tahunAjaran;
                    $tagihan->total            = $tipe->total;
                    $tagihan->save();
                }
            }

            $jenjangId++;

            // foreach ($siswaXI as $siswa) {
            //     $historyKelas               = new HistoryKelas();
            //     $historyKelas->siswa_id     = $siswa->id;
            //     $historyKelas->jenjang_id   = 2;
            //     $historyKelas->tahun_ajaran = $tahunAjaran;
            //     $historyKelas->kelas        = "XI";
            //     $historyKelas->save();

            //     $tipeTagihan = TipeTagihan::where('tahun_ajaran', $tahunAjaran)->where('jenjang_id', 2)->get();

            //     foreach ($tipeTagihan as $tipe) {
            //         $tagihan                  = new TagihanNew();
            //         $tagihan->siswa_id        = $siswa->id;
            //         $tagihan->tipe_tagihan_id = $tipe->id;
            //         $tagihan->jenjang_id      = $tipe->jenjang_id;
            //         $tagihan->tahun_ajaran    = $tahunAjaran;
            //         $tagihan->save();
            //     }
            // }

            // foreach ($siswaXII as $siswa) {
            //     $historyKelas               = new HistoryKelas();
            //     $historyKelas->siswa_id     = $siswa->id;
            //     $historyKelas->jenjang_id   = 3;
            //     $historyKelas->tahun_ajaran = $tahunAjaran;
            //     $historyKelas->kelas        = "XII";
            //     $historyKelas->save();

            //     $tipeTagihan = TipeTagihan::where('tahun_ajaran', $tahunAjaran)->where('jenjang_id', 3)->get();

            //     foreach ($tipeTagihan as $tipe) {
            //         $tagihan                  = new TagihanNew();
            //         $tagihan->siswa_id        = $siswa->id;
            //         $tagihan->tipe_tagihan_id = $tipe->id;
            //         $tagihan->jenjang_id      = $tipe->jenjang_id;
            //         $tagihan->tahun_ajaran    = $tahunAjaran;
            //         $tagihan->save();
            //     }
            // }
        }
    }
}
