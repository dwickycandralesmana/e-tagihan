<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class KelasExport implements FromView
{
    public function __construct(public $kelas, public $tagihan, public $tahun_ajaran) {}

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        $kelas = $this->kelas;
        $tagihan = $this->tagihan;
        $tahun_ajaran = $this->tahun_ajaran;

        return view('admin.report.export.kelas', compact('kelas', 'tagihan', 'tahun_ajaran'));
    }
}
