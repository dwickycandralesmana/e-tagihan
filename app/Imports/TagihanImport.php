<?php

namespace App\Imports;

use App\Models\Jenjang;
use App\Models\Tagihan;
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
        $column  = json_decode($jenjang->column, true) ?? [];
        $alias   = [];

        foreach ($column as $key => $value) {
            $alias[$column[$key]['key']] = $column[$key]['label'];
        }

        Tagihan::where('jenjang_id', $this->jenjang_id)->delete();

        foreach ($collection as $key => $row) {
            $tagihan             = Tagihan::firstOrNew(['nis' => $row['nis']]);
            $tagihan->nama       = $row['nama'];
            $tagihan->kelas      = $row['kelas'];
            $tagihan->jenjang_id = $this->jenjang_id;

            unset($row['nama']);
            unset($row['kelas']);
            unset($row['nis']);

            $row = $row->toArray();

            $column = [];

            foreach ($alias as $key => $value) {
                $column[] = [
                    'key' => $key,
                    'label' => $value,
                    'value' => $row[$key] ?? 0,
                ];
            }

            $tagihan->column = json_encode($column);
            $tagihan->save();
        }
    }
}
