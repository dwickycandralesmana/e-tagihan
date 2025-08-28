<?php

namespace App\Http\Controllers;

use App\Exports\KelasExport;
use App\Exports\SiswaExport;
use App\Models\HistoryKelas;
use App\Models\Siswa;
use App\Models\TipeTagihan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function siswa()
    {
        $this->listKelas = HistoryKelas::groupBy('kelas')->pluck('kelas');

        return view('admin.report.siswa', $this->data);
    }

    public function siswaData(Request $request)
    {
        $data = HistoryKelas::query()
            ->with('jenjang', 'siswa');

        if ($request->tahun_ajaran) {
            $data->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->kelas) {
            $data->where('kelas', $request->kelas);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('price', function ($row) {
                return "Rp " . number_format($row->price, 0, ',', '.');
            })
            ->editColumn('admin_fee', function ($row) {
                return "Rp " . number_format($row->admin_fee, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $html = "";

                if (auth()->user()) {
                    $html .= "<a href='" . route('laporan.siswa.export', $row->id) . "' class='btn btn-success me-1 mb-1'><i class='fas fa-file-excel'></i> Download Report</a>";
                }

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function siswaExport($id)
    {
        $kelas = HistoryKelas::query()
            ->with('jenjang', 'siswa', 'tagihans', 'tagihans.tipe_tagihan', 'tagihans.pembayaran_details')
            ->where('id', $id)
            ->first();

        $this->kelas = $kelas;

        // return view('admin.report.export.pembayaran-siswa', $this->data);

        return Excel::download(new SiswaExport($kelas), $kelas->siswa->nama . ' - ' . $kelas->kelas . ' - ' . $kelas->tahun_ajaran . '.xlsx');
    }

    public function kelas()
    {
        $this->listKelas = HistoryKelas::groupBy('kelas')->pluck('kelas');

        return view('admin.report.kelas', $this->data);
    }

    public function kelasExport(Request $request)
    {
        $kelas = HistoryKelas::query()
            ->with('jenjang', 'siswa', 'tagihans', 'tagihans.tipe_tagihan', 'tagihans.pembayaran_details')
            ->where('kelas', $request->kelas)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->get();

        $jenjangId   = $kelas->first()->jenjang_id;
        $tahunAjaran = $kelas->first()->tahun_ajaran;
        $namaKelas   = $kelas->first()->kelas;

        $tagihan = TipeTagihan::whereJenjangId($jenjangId)->whereTahunAjaran($tahunAjaran)->get();

        $this->kelas = $kelas;
        $this->tagihan = $tagihan;
        $this->tahun_ajaran = $tahunAjaran;

        // return view('admin.report.export.kelas', $this->data);

        return Excel::download(new KelasExport($kelas, $tagihan, $tahunAjaran), $namaKelas . ' - ' . $tahunAjaran . '.xlsx');
    }
}
