<?php

namespace Database\Seeders;

use App\Models\Jenjang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenjangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jenjang::create([
            'nama'    => 'X',
            'catatan' => '
                <p>
                    1. Pembaruan tagihan siswa terakhir pada tanggal:  15 MEI 2024 <br>
                    2. Tagihan siswa diatas merupakan tagihan sampai bulan MEI 2024 <br>
                    3. Bagi yang menghendaki transfer bank mohon bisa menghubungi wali kelas. <br>
                    4. Mohon segera konfirmasi ke bagian bendahara jika terjadi kesalahan atau jika ada hal yang ingin ditanyakan. Terima kasih. <br>
                </p>
            ',
            'column'  => '[{"key":"daftar_ulang_total","label":"TOTAL DAFTAR ULANG"},{"key":"daftar_ulang_dibayar","label":"DAFTAR ULANG YANG TELAH DIBAYAR"},{"key":"spp_kurang","label":"KEKURANGAN SPP"},{"key":"dafar_ulang_kurang","label":"KEKURANGAN DAFTAR ULANG"},{"key":"kalender_2024","label":"KEKURANGAN KALENDER 2024"},{"key":"map_rapor","label":"KEKURANGAN MAP RAPOR"},{"key":"total_tagihan","label":"TOTAL TAGIHAN"}]',
        ]);

        Jenjang::create([
            'nama'    => 'XI',
            'catatan' => '
                <p>
                    1. Pembaruan tagihan siswa terakhir pada tanggal:  15 MEI 2024 <br>
                    2. Tagihan siswa diatas merupakan tagihan sampai bulan MEI 2024 <br>
                    3. Bagi yang menghendaki transfer bank mohon bisa menghubungi wali kelas. <br>
                    4. Mohon segera konfirmasi ke bagian bendahara jika terjadi kesalahan atau jika ada hal yang ingin ditanyakan. Terima kasih. <br>
                </p>
            ',
            'column'  => '[{"key":"tunggakan","label":"TUNGGAKAN X"},{"key":"total_tunggakan","label":"TOTAL TUNGGAKAN"},{"key":"spp_kurang","label":"KEKURANGAN SPP KELAS XI"},{"key":"praktek_kurang","label":"KEKURANGAN BIAYA PRAKTEK SISWA"},{"key":"kunjungan_industri_kurang","label":"KEKURANGAN BIAYA KUNJUNGAN INDUSTRI"},{"key":"kalender_2024","label":"KEKURANGAN KALENDER 2024"},{"key":"prakerin_kurang","label":"KEKURANGAN BIAYA PRAKERIN 1"},{"key":"total_tagihan","label":"TOTAL TAGIHAN"}]',
        ]);

        Jenjang::create([
            'nama'    => 'XII',
            'catatan' => '
                <p>
                    1. Pembaruan tagihan siswa terakhir pada tanggal:  15 MEI 2024 <br>
                    2. Tagihan siswa diatas merupakan tagihan sampai bulan MEI 2024 <br>
                    3. Bagi yang menghendaki transfer bank mohon bisa menghubungi wali kelas. <br>
                    4. Mohon segera konfirmasi ke bagian bendahara jika terjadi kesalahan atau jika ada hal yang ingin ditanyakan. Terima kasih. <br>
                </p>
            ',
            'column'  => '[{"key":"tunggakan","label":"TUNGGAKAN X"},{"key":"total_tunggakan","label":"TOTAL TUNGGAKAN"},{"key":"spp_kurang","label":"KEKURANGAN SPP KELAS XII"},{"key":"praktek_kurang","label":"KEKURANGAN BIAYA PRAKTEK SISWA"},{"key":"ukk_kurang","label":"KEKURANGAN BIAYA UKK XII"},{"key":"kalender_2024","label":"KEKURANGAN KALENDER 2024"},{"key":"prakerin_kurang","label":"KEKURANGAN BIAYA PRAKERIN 2"},{"key":"total_tagihan","label":"TOTAL TAGIHAN"}]',
        ]);
    }
}
