<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\Jenjang;
use App\Models\TipeTagihan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class JenisPembayaranController extends BaseController
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
        return view('admin.jenis-pembayaran.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->jenjang = Jenjang::all();

        return view('admin.jenis-pembayaran.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required',
            'jenjang_id'   => 'required',
            'tahun_ajaran' => 'required',
            'total'        => 'required',
        ]);

        DB::beginTransaction();
        try {
            $tipe               = new TipeTagihan();
            $tipe->nama         = $request->nama;
            $tipe->jenjang_id   = $request->jenjang_id;
            $tipe->tahun_ajaran = $request->tahun_ajaran;
            $tipe->total        = $request->total;
            $tipe->save();

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

        return redirect()->route('jenis-pembayaran.index')->with($notification);
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
        $this->tipe    = TipeTagihan::find($id);
        $this->jenjang = Jenjang::all();

        return view('admin.jenis-pembayaran.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'         => 'required',
            'total'        => 'required',
            'jenjang_id'   => 'required',
            'tahun_ajaran' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $tipe               = TipeTagihan::find($id);
            $tipe->nama         = $request->nama;
            $tipe->jenjang_id   = $request->jenjang_id;
            $tipe->tahun_ajaran = $request->tahun_ajaran;
            $tipe->total        = $request->total;
            $tipe->save();

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

        return redirect()->route('jenis-pembayaran.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $tipe = TipeTagihan::findOrFail($id);
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
            'message' => 'Data user berhasil dihapus!',
        );

        return response()->json($notification);
    }

    public function data(Request $request)
    {
        $data = TipeTagihan::query()
            ->with('jenjang');

        if ($request->has('tahun_ajaran') && $request->tahun_ajaran) {
            $data->where('tahun_ajaran', $request->tahun_ajaran);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal_lahir', function ($row) {
                return date('j F Y', strtotime($row->tanggal_lahir));
            })
            ->editColumn('total', function ($row) {
                return formatRp($row->total);
            })
            ->addColumn('action', function ($row) {
                $html = "";

                if (auth()->user()) {
                    $html .= "<a href='" . route('jenis-pembayaran.edit', $row->id) . "' class='btn btn-warning me-1 mb-1'><i class='fas fa-edit'></i> Edit</a>";

                    if (!$row->is_default) {
                        $html .= "<button class='btn btn-danger btn-delete mb-1 me-1' data-id='" . $row->id . "'><i class='fas fa-trash'></i> Hapus</button>";
                    }
                }

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
