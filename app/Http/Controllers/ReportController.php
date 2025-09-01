<?php

namespace App\Http\Controllers;

use App\Exports\KelasExport;
use App\Exports\PotonganExport;
use App\Exports\SiswaExport;
use App\Models\HistoryKelas;
use App\Models\PembayaranDetail;
use App\Models\Siswa;
use App\Models\TipeTagihan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return view('admin.report.export.pembayaran-siswa', $this->data);

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

    public function potongan()
    {
        $this->listKelas = HistoryKelas::select('kelas', DB::raw('MAX(jenjang_id) as jenjang_id'))->groupBy('kelas')->get();
        $this->bendahara = User::where('name', '!=', 'Admin')->get();

        return view('admin.report.potongan', $this->data);
    }

    public function potonganData(Request $request)
    {
        $date        = explode(" - ", $request->tanggal_pembayaran);
        $startDate   = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
        $endDate     = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');
        $bendahara   = $request->bendahara;
        $tipeTagihan = $request->jenis_pembayaran;
        $tahunAjaran = $request->tahun_ajaran;

        $data = PembayaranDetail::query()
            ->join('pembayarans', 'pembayaran_details.pembayaran_id', '=', 'pembayarans.id')
            ->join('history_kelas', 'pembayaran_details.history_kelas_id', '=', 'history_kelas.id')
            ->join('tagihan_news', 'pembayaran_details.tagihan_new_id', '=', 'tagihan_news.id')
            ->join('tipe_tagihans', 'tagihan_news.tipe_tagihan_id', '=', 'tipe_tagihans.id')
            ->join('siswas', 'history_kelas.siswa_id', '=', 'siswas.id')
            ->join('users', 'pembayarans.created_by', '=', 'users.id')
            ->select('pembayaran_details.*', 'siswas.nama', 'pembayarans.tanggal_pembayaran', 'history_kelas.kelas as nama_kelas', 'tipe_tagihans.nama as tipe_tagihan', 'users.name as created_by')
            ->whereBetween('pembayarans.tanggal_pembayaran', [$startDate, $endDate])
            ->where('pembayaran_details.tahun_ajaran', $tahunAjaran)
            ->where('pembayaran_details.potongan', '>', 0);

        if ($bendahara) {
            $data->where('pembayarans.created_by', $bendahara);
        }

        if ($request->kelas) {
            $data->where('history_kelas.kelas', $request->kelas);
        }

        if ($tipeTagihan) {
            $data->where('tagihan_news.tipe_tagihan_id', $tipeTagihan);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('potongan', function ($row) {
                return formatRp($row->potongan);
            })
            ->editColumn('tipe_tagihan', function ($row) {
                return $row->tipe_tagihan . $row->bulan_text;
            })
            ->addColumn('action', function ($row) {
                $html = "";


                if (auth()->user()) {
                    if ($row->pembayaran->created_by == auth()->user()->id) {
                        $html .= "<a href='" . route('pembayaran.edit', $row->id) . "' class='btn btn-warning me-1 mb-1'><i class='fas fa-edit'></i> Edit</a>";
                    }

                    $html .= "<a href='" . route('pembayaran.pdf', $row->pembayaran_id) . "' target='_blank' class='btn btn-primary me-1 mb-1'><i class='fas fa-eye'></i> Bukti Bayar</a>";
                }

                return $html;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function jenisPembayaranData(Request $request)
    {
        $kelas = HistoryKelas::query()
            ->where('kelas', $request->kelas)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->first();

        $list = [];

        if (!$kelas) {
            return response()->json($list);
        }

        $list = TipeTagihan::whereJenjangId($kelas->jenjang_id)->whereTahunAjaran($kelas->tahun_ajaran)->get()->toArray();

        return response()->json($list);
    }

    public function potonganExport(Request $request)
    {

        $date        = explode(" - ", $request->tanggal_pembayaran);
        $startDate   = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
        $endDate     = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');
        $bendahara   = $request->bendahara;
        $tipeTagihan = $request->jenis_pembayaran;
        $tahunAjaran = $request->tahun_ajaran;

        $data = PembayaranDetail::query()
            ->join('pembayarans', 'pembayaran_details.pembayaran_id', '=', 'pembayarans.id')
            ->join('history_kelas', 'pembayaran_details.history_kelas_id', '=', 'history_kelas.id')
            ->join('tagihan_news', 'pembayaran_details.tagihan_new_id', '=', 'tagihan_news.id')
            ->join('tipe_tagihans', 'tagihan_news.tipe_tagihan_id', '=', 'tipe_tagihans.id')
            ->join('siswas', 'history_kelas.siswa_id', '=', 'siswas.id')
            ->join('users', 'pembayarans.created_by', '=', 'users.id')
            ->select('pembayaran_details.*', 'siswas.nama', 'pembayarans.tanggal_pembayaran', 'history_kelas.kelas as nama_kelas', 'tipe_tagihans.nama as tipe_tagihan', 'users.name as created_by')
            ->whereBetween('pembayarans.tanggal_pembayaran', [$startDate, $endDate])
            ->where('pembayaran_details.tahun_ajaran', $tahunAjaran)
            ->where('pembayaran_details.potongan', '>', 0);

        if ($bendahara) {
            $data->where('pembayarans.created_by', $bendahara);
        }

        if ($request->kelas) {
            $data->where('history_kelas.kelas', $request->kelas);
        }

        if ($tipeTagihan) {
            $data->where('tagihan_news.tipe_tagihan_id', $tipeTagihan);
        }

        $data = $data->get();

        $fileName = date('Ymd') . ' Potongan.xlsx';

        return Excel::download(new PotonganExport($data), $fileName);
    }
}
