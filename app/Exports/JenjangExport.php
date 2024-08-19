<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class JenjangExport implements FromView
{
    public function __construct(public $jenjang) {}
    public function view(): View
    {
        $jenjang = $this->jenjang;

        return view('export.jenjang', [
            'jenjang' => $jenjang,
        ]);
    }
}
