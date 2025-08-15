<?php

namespace Database\Seeders;

use App\Models\Jenjang;
use App\Models\TipeTagihan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipeTagihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenjangs = Jenjang::get();
        for ($i = 2019; $i <= 2035; $i++) {
            //X
            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 1;
            $tipe->key          = 'daftar_ulang';
            $tipe->nama         = 'Daftar Ulang';
            $tipe->total        = 0;
            $tipe->save();

            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 1;
            $tipe->key          = 'spp';
            $tipe->nama         = 'SPP Kelas X';
            $tipe->total        = 0;
            $tipe->save();

            //XI
            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 2;
            $tipe->key          = 'tunggakan_kelas_x';
            $tipe->nama         = 'Tunggakan Kelas X';
            $tipe->total        = 0;
            $tipe->save();

            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 2;
            $tipe->key          = 'spp';
            $tipe->nama         = 'SPP Kelas XI';
            $tipe->total        = 0;
            $tipe->save();

            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 2;
            $tipe->key          = 'biaya_praktek';
            $tipe->nama         = 'Biaya Praktek';
            $tipe->total        = 0;
            $tipe->save();

            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 2;
            $tipe->key          = 'prakerin';
            $tipe->nama         = 'Prakerin';
            $tipe->total        = 0;
            $tipe->save();

            //XII
            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 3;
            $tipe->key          = 'tunggakan_kelas_xi';
            $tipe->nama         = 'Tunggakan Kelas XI';
            $tipe->total        = 0;
            $tipe->save();

            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 3;
            $tipe->key          = 'spp';
            $tipe->nama         = 'SPP Kelas XII';
            $tipe->total        = 0;
            $tipe->save();

            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 3;
            $tipe->key          = 'angsuran_ujian';
            $tipe->nama         = 'Angsuran Ujian Kelas XII';
            $tipe->total        = 0;
            $tipe->save();

            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 3;
            $tipe->key          = 'biaya_praktek';
            $tipe->nama         = 'Biaya Praktek';
            $tipe->total        = 0;
            $tipe->save();

            $tipe               = new TipeTagihan();
            $tipe->tahun_ajaran = $i;
            $tipe->jenjang_id   = 3;
            $tipe->key          = 'prakerin';
            $tipe->nama         = 'Prakerin';
            $tipe->total        = 0;
            $tipe->save();
        }
    }
}
