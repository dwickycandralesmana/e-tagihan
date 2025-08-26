<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\HistoryKelas;
use App\Models\Jenjang;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\TagihanNew;
use App\Models\TipeTagihan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PembayaranController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pembayaran.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $historyKelas = HistoryKelas::findOrFail(decryptWithKey($request->id));

        $this->jenjang      = Jenjang::all();
        $this->historyKelas = $historyKelas;
        $this->details      = $historyKelas->tagihans;

        $this->paidMonth = PembayaranDetail::whereHas('pembayaran', function ($query) use ($historyKelas) {
            $query->where('history_kelas_id', $historyKelas->id);
        })
            ->where('bulan', '!=', null)
            ->where('key', '!=', 'angsuran_ujian')
            ->pluck('bulan')
            ->toArray();

        $this->paidMonthAngsuran = PembayaranDetail::whereHas('pembayaran', function ($query) use ($historyKelas) {
            $query->where('history_kelas_id', $historyKelas->id);
        })
            ->where('bulan', '!=', null)
            ->where('key', 'angsuran_ujian')
            ->pluck('bulan')
            ->toArray();

        return view('admin.pembayaran.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'history_kelas_id'   => 'required',
            'tanggal_pembayaran' => 'required',
            'metode_pembayaran'  => 'required',
        ]);

        DB::beginTransaction();
        try {
            $historyKelas = HistoryKelas::find($request->history_kelas_id);

            $pembayaran                     = new Pembayaran();
            $pembayaran->code               = date('Ymd') . strtoupper(Str::random(6));
            $pembayaran->siswa_id           = $historyKelas->siswa_id;
            $pembayaran->jenjang_id         = $historyKelas->jenjang_id;
            $pembayaran->tahun_ajaran       = $historyKelas->tahun_ajaran;
            $pembayaran->kelas              = $historyKelas->kelas;
            $pembayaran->tanggal_pembayaran = $request->tanggal_pembayaran;
            $pembayaran->metode_pembayaran  = $request->metode_pembayaran;
            $pembayaran->created_by         = Auth::user()->id;
            $pembayaran->updated_by         = Auth::user()->id;
            $pembayaran->history_kelas_id   = $historyKelas->id;
            $pembayaran->save();

            $totalBayar    = 0;
            $totalPotongan = 0;

            foreach ($request->details as $key => $value) {
                $bayar    = (float)$value['bayar'] ?? 0;
                $potongan = (float)$value['potongan'] ?? 0;
                $jumlah   = $bayar + $potongan;

                $tagihanNew = TagihanNew::with('tipe_tagihan')->find($value['tagihan_new_id']);
                $tagihanNew->deskripsi = $value['deskripsi'] ?? null;
                $tagihanNew->save();

                $pembayaranDetail                   = new PembayaranDetail();
                $pembayaranDetail->pembayaran_id    = $pembayaran->id;
                $pembayaranDetail->tagihan_new_id   = $value['tagihan_new_id'];
                $pembayaranDetail->siswa_id         = $historyKelas->siswa_id;
                $pembayaranDetail->jenjang_id       = $historyKelas->jenjang_id;
                $pembayaranDetail->tahun_ajaran     = $historyKelas->tahun_ajaran;
                $pembayaranDetail->bulan            = $value['bulan'] ?? ($value['bulan_angsuran'] ?? null);
                $pembayaranDetail->kelas            = $value['kelas'] ?? null;
                $pembayaranDetail->bayar            = $bayar;
                $pembayaranDetail->potongan         = $potongan;
                $pembayaranDetail->jumlah           = $jumlah;
                $pembayaranDetail->key              = $tagihanNew->tipe_tagihan->key;
                $pembayaranDetail->history_kelas_id = $historyKelas->id;
                $pembayaranDetail->save();

                $totalBayar += $bayar;
                $totalPotongan += $potongan;
            }

            $pembayaran->total_bayar    = $totalBayar;
            $pembayaran->total_potongan = $totalPotongan;
            $pembayaran->save();

            $notification = array(
                'message'    => 'Data berhasil disimpan',
                'alert-type' => 'success',
                'status'     => true,
                'url'        => route('pembayaran.pdf', $pembayaran->id),
            );
        } catch (\Exception $e) {
            $notification = array(
                'message'    => 'Data gagal disimpan',
                'alert-type' => 'error',
                'status'     => false,
            );

            DB::rollBack();

            return response()->json($notification);
        }

        DB::commit();

        return response()->json($notification);

        return redirect()->route('pembayaran.index')->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->pembayaran = Pembayaran::find($id);

        if ($this->pembayaran->created_by != Auth::user()->id) {
            abort(403);
        }

        $historyKelas       = HistoryKelas::find($this->pembayaran->history_kelas_id);
        $this->historyKelas = $historyKelas;
        $this->details      = $this->historyKelas->tagihans;
        $this->jenjang      = Jenjang::all();

        $this->paidMonth = PembayaranDetail::whereHas('pembayaran', function ($query) use ($historyKelas) {
            $query->where('history_kelas_id', $historyKelas->id);
        })
            ->where('bulan', '!=', null)
            ->where('key', '!=', 'angsuran_ujian')
            ->pluck('bulan')
            ->toArray();

        $this->paidMonthAngsuran = PembayaranDetail::whereHas('pembayaran', function ($query) use ($historyKelas) {
            $query->where('history_kelas_id', $historyKelas->id);
        })
            ->where('bulan', '!=', null)
            ->where('key', 'angsuran_ujian')
            ->pluck('bulan')
            ->toArray();

        return view('admin.pembayaran.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'history_kelas_id'   => 'required',
            'tanggal_pembayaran' => 'required',
            'metode_pembayaran'  => 'required',
        ]);

        DB::beginTransaction();
        try {
            $historyKelas = HistoryKelas::find($request->history_kelas_id);

            $pembayaran                     = Pembayaran::find($id);
            $pembayaran->siswa_id           = $historyKelas->siswa_id;
            $pembayaran->jenjang_id         = $historyKelas->jenjang_id;
            $pembayaran->tahun_ajaran       = $historyKelas->tahun_ajaran;
            $pembayaran->kelas              = $historyKelas->kelas;
            $pembayaran->tanggal_pembayaran = $request->tanggal_pembayaran;
            $pembayaran->metode_pembayaran  = $request->metode_pembayaran;
            $pembayaran->created_by         = Auth::user()->id;
            $pembayaran->updated_by         = Auth::user()->id;
            $pembayaran->history_kelas_id   = $historyKelas->id;
            $pembayaran->save();

            $totalBayar    = 0;
            $totalPotongan = 0;

            PembayaranDetail::where('pembayaran_id', $id)->delete();

            foreach ($request->details as $key => $value) {
                $bayar    = (float)$value['bayar'] ?? 0;
                $potongan = (float)$value['potongan'] ?? 0;
                $jumlah   = $bayar + $potongan;

                $tagihanNew = TagihanNew::with('tipe_tagihan')->find($value['tagihan_new_id']);

                $pembayaranDetail                   = new PembayaranDetail();
                $pembayaranDetail->pembayaran_id    = $pembayaran->id;
                $pembayaranDetail->tagihan_new_id   = $value['tagihan_new_id'];
                $pembayaranDetail->siswa_id         = $historyKelas->siswa_id;
                $pembayaranDetail->jenjang_id       = $historyKelas->jenjang_id;
                $pembayaranDetail->tahun_ajaran     = $historyKelas->tahun_ajaran;
                $pembayaranDetail->bulan            = $value['bulan'] ?? null;
                $pembayaranDetail->kelas            = $value['kelas'] ?? null;
                $pembayaranDetail->bayar            = $bayar;
                $pembayaranDetail->potongan         = $potongan;
                $pembayaranDetail->jumlah           = $jumlah;
                $pembayaranDetail->key              = $tagihanNew->tipe_tagihan->key;
                $pembayaranDetail->history_kelas_id = $historyKelas->id;
                $pembayaranDetail->save();
            }

            $pembayaran->total_bayar    = PembayaranDetail::where('pembayaran_id', $pembayaran->id)->sum('bayar');
            $pembayaran->total_potongan = PembayaranDetail::where('pembayaran_id', $pembayaran->id)->sum('potongan');
            $pembayaran->save();

            $notification = array(
                'message'    => 'Data berhasil disimpan',
                'alert-type' => 'success'
            );
        } catch (\Exception $e) {
            $notification = array(
                'message'    => 'Data gagal disimpan',
                'alert-type' => 'error'
            );

            DB::rollBack();

            return redirect()->back()->with($notification);
        }

        DB::commit();

        return redirect()->route('pembayaran.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $tipe = Pembayaran::findOrFail($id);
            if ($tipe->created_by != auth()->user()->id) {
                throw new Exception("Anda tidak memiliki izin untuk menghapus data ini");
            }
            $tipe->delete();
        } catch (Exception $e) {
            DB::rollback();

            $notification = array(
                'message'    => $e->getMessage(),
                'alert-type' => 'danger',
                'status'     => false,
            );

            return response()->json($notification);
        }

        DB::commit();

        $notification = array(
            'status' => true,
            'message' => 'Data berhasil dihapus!',
        );

        return response()->json($notification);
    }

    public function data(Request $request)
    {
        $date = explode(" - ", $request->tanggal_pembayaran);
        $startDate = Carbon::createFromFormat('d/m/Y', $date[0])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d');

        $data = Pembayaran::query()
            ->with('historyKelas', 'historyKelas.siswa', 'historyKelas.jenjang', 'createdBy', 'updatedBy');

        if ($request->has('tahun_ajaran') && $request->tahun_ajaran) {
            $data->where('tahun_ajaran', $request->tahun_ajaran);
        }

        if ($request->has('tanggal_pembayaran') && $request->tanggal_pembayaran) {
            $data->whereBetween('tanggal_pembayaran', [$startDate, $endDate]);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal_lahir', function ($row) {
                return date('j F Y', strtotime($row->tanggal_lahir));
            })
            ->editColumn('total_bayar', function ($row) {
                return formatRp($row->total_bayar);
            })
            ->editColumn('total_potongan', function ($row) {
                return formatRp($row->total_potongan);
            })
            ->addColumn('details', function ($row) {
                return $row->details->map(function ($detail) {
                    $year = $detail->bulan >= 1 && $detail->bulan <= 6 ? $detail->tahun_ajaran + 1 : $detail->tahun_ajaran;
                    $bulan = $detail->bulan ? " - " . Carbon::parse($year . '-' . $detail->bulan . '-01')->format('M') : "";

                    return $detail->tagihanNew->tipe_tagihan->nama . $bulan . " " . $year;
                })->implode(', ');
            })
            ->addColumn('action', function ($row) {
                $html = "";

                if (auth()->user() && $row->created_by == auth()->user()->id) {
                    $html .= "<a href='" . route('pembayaran.edit', $row->id) . "' class='btn btn-warning me-1 mb-1'><i class='fas fa-edit'></i> Edit</a>";

                    if (!$row->is_default) {
                        $html .= "<button class='btn btn-danger btn-delete mb-1 me-1' data-id='" . $row->id . "'><i class='fas fa-trash'></i> Hapus</button>";
                    }
                }

                $html .= "<a href='" . route('pembayaran.pdf', $row->id) . "' target='_blank' class='btn btn-primary me-1 mb-1'><i class='fas fa-eye'></i> Bukti Bayar</a>";

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function pdf($id)
    {
        $pembayaran = Pembayaran::find($id);
        $pdf = Pdf::loadView('admin.pembayaran.pdf', compact('pembayaran'));

        return $pdf->stream('Bukti Pembayaran - ' . $pembayaran->siswa->nama . '.pdf');
    }
}
