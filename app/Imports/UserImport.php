<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithCalculatedFormulas, WithHeadingRow
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
            $tanggalLahir = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["tanggal_lahir"]));

            $user = User::firstOrNew([
                'email' => $row["email"]
            ]);

            $user->name          = $row["nama"];
            $user->email         = $row["email"];
            $user->tanggal_lahir = $tanggalLahir;
            $user->tempat_lahir  = $row["tempat_lahir"];
            $user->jenis_kelamin = $row["jenis_kelamin"];
            $user->alamat        = $row["alamat"];
            $user->nomor_hp      = $row["nomor_hp"];
            $user->type          = "user";

            if (!$user->exists) {
                $user->password = bcrypt($row["password"]);
            }

            $user->save();
        }
    }
}
