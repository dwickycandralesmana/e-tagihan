<?php

namespace App\Imports;

use App\Models\JawabanSoalUjian;
use App\Models\SoalUjian;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SoalImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    public function __construct(public $ujian_id = null)
    {
    }

    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        if ($this->ujian_id) {
            $ujian = Ujian::find($this->ujian_id);
        } else {
            $ujian                 = new Ujian();
            $ujian->nama           = 'Ujian 1';
            $ujian->kode           = 'UJN1';
            $ujian->jenis_ujian_id = 2;
            $ujian->start_date     = Carbon::now()->format('Y-m-d 00:00:00');
            $ujian->end_date       = Carbon::now()->addDays(15)->format('Y-m-d 23:59:59');
            $ujian->durasi         = 120;
            $ujian->harga          = 100000;
            // $ujian->list_nilai     = json_encode(['TWK' => 70, 'TIU' => 70, 'TKP' => 70]);
            $ujian->list_nilai     = json_encode(["Tes Kompetensi Teknis" => 200, "Tes Kompetensi Manajerial & Sosial Kultural" => 117, "Tes Wawancara" => 24]);
            $ujian->save();
        }

        $tipeBobot = ["TKP", "Tes Kompetensi Manajerial", "Tes Kompetensi Sosial Kultural", "Tes Wawancara"];

        foreach ($collection as $row) {
            $soal = new SoalUjian();
            $soal->ujian_id = $ujian->id;
            $soal->tipe = $row['tipe'];
            $soal->soal = $row['soal'];
            $soal->pembahasan = $row['pembahasan'];
            $soal->save();

            $option = range('a', 'e');
            foreach ($option as $key => $value) {
                if (!$row['opsi_' . $value]) {
                    continue;
                }

                $jawaban                = new JawabanSoalUjian();
                $jawaban->soal_ujian_id = $soal->id;
                $jawaban->jawaban       = $row['opsi_' . $value];
                if (in_array($soal->tipe, $tipeBobot)) {
                    $jawaban->is_benar = true;
                    $jawaban->bobot    = $row['bobot_' . $value] ?? 5;
                } else {
                    $jawaban->is_benar      = strtolower($row['kunci']) == $value ? true : false;
                    $jawaban->bobot         = strtolower($row['kunci']) == $value ? 5 : 0;
                }

                $jawaban->save();
            }
        }
    }
}
