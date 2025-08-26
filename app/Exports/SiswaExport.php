<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SiswaExport implements FromView
{
    public function __construct(public $kelas) {}

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $kelas = $this->kelas;

        return view('admin.report.export.siswa', compact('kelas'));
    }
}
