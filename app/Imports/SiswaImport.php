<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function headingRow(): int
    {
        return 1;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $siswa = Siswa::firstOrNew([
                'nis' => $row['nis'],
            ]);

            $siswa->nama = $row['nama'];
            $siswa->nis  = $row['nis'];
            $siswa->save();
        }
    }
}
