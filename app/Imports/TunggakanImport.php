<?php

namespace App\Imports;

use App\Models\Jenjang;
use App\Models\Siswa;
use App\Models\TagihanNew;
use App\Models\TipeTagihan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TunggakanImport implements ToCollection, WithHeadingRow
{
    public function __construct(public $jenjang_id) {}

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $jenjang = Jenjang::find($this->jenjang_id);

        foreach ($collection as $key => $row) {
            $siswa       = Siswa::where('nis', $row['nis'])->first();
            if (!$siswa) {
                continue;
            }

            $tipeTagihan = TipeTagihan::where('tahun_ajaran', $row['tahun_ajaran'])->where('jenjang_id', $jenjang->id)->where('key', $row['key'])->first();
            if (!$tipeTagihan) {
                continue;
            }

            $tagihanNew  = TagihanNew::where('siswa_id', $siswa->id)->where('jenjang_id', $jenjang->id)->where('tipe_tagihan_id', $tipeTagihan->id)->where('tahun_ajaran', $row['tahun_ajaran'])->first();
            if (!$tagihanNew) {
                continue;
            }

            $tagihanNew->total     = $row['total'];
            $tagihanNew->potongan  = $row['potongan'];
            $tagihanNew->deskripsi = $row['deskripsi'];
            $tagihanNew->save();
        }
    }
}
