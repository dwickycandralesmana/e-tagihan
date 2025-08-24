<?php

namespace App\Imports;

use App\Models\HistoryKelas;
use App\Models\Jenjang;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\TagihanNew;
use App\Models\TipeTagihan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TagihanImport implements ToCollection, WithHeadingRow
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
        // $column  = json_decode($jenjang->column, true) ?? [];
        // $alias   = [];

        // foreach ($column as $key => $value) {
        //     $alias[$column[$key]['key']] = $column[$key]['label'];
        // }

        // Tagihan::where('jenjang_id', $this->jenjang_id)->delete();

        foreach ($collection as $key => $row) {
            $siswa = Siswa::where('nis', $row['nis'])->first();

            if (!$siswa) {
                continue;
            }

            $historyKelas               = HistoryKelas::firstOrNew([
                'siswa_id'     => $siswa->id,
                'jenjang_id'   => $this->jenjang_id,
                'tahun_ajaran' => $row['tahun_ajaran'],
            ]);

            $historyKelas->siswa_id     = $siswa->id;
            $historyKelas->jenjang_id   = $this->jenjang_id;
            $historyKelas->tahun_ajaran = $row['tahun_ajaran'];
            $historyKelas->kelas        = $row['kelas'];
            $historyKelas->save();

            $tipeTagihan = TipeTagihan::where('tahun_ajaran', $row['tahun_ajaran'])->where('jenjang_id', $this->jenjang_id)->get();

            foreach ($tipeTagihan as $tipe) {
                $tagihan                   = TagihanNew::updateOrCreate([
                    'siswa_id'         => $siswa->id,
                    'history_kelas_id' => $historyKelas->id,
                    'tipe_tagihan_id'  => $tipe->id,
                    'jenjang_id'       => $tipe->jenjang_id,
                    'tahun_ajaran'     => $row['tahun_ajaran'],
                ]);

                $tagihan->siswa_id         = $siswa->id;
                $tagihan->history_kelas_id = $historyKelas->id;
                $tagihan->tipe_tagihan_id  = $tipe->id;
                $tagihan->jenjang_id       = $tipe->jenjang_id;
                $tagihan->tahun_ajaran     = $row['tahun_ajaran'];
                $tagihan->total            = $tipe->total;
                $tagihan->save();
            }

            // $tagihan             = Tagihan::firstOrNew(['nis' => $row['nis']]);
            // $tagihan->nama       = $row['nama'];
            // $tagihan->kelas      = $row['kelas'];
            // $tagihan->jenjang_id = $this->jenjang_id;

            // unset($row['nama']);
            // unset($row['kelas']);
            // unset($row['nis']);

            // $row = $row->toArray();

            // $column = [];

            // foreach ($alias as $key => $value) {
            //     $column[] = [
            //         'key' => $key,
            //         'label' => $value,
            //         'value' => $row[$key] ?? 0,
            //     ];
            // }

            // $tagihan->column = json_encode($column);
            // $tagihan->save();
        }
    }
}
