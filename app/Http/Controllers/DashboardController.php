<?php

namespace App\Http\Controllers;

use App\Imports\TagihanImport;
use App\Models\Jenjang;
use App\Models\Tagihan;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('admin.dashboard.index', $this->data);
    }

    public function tagihan()
    {
        $this->jenjang = Jenjang::all();

        return view('admin.tagihan.index', $this->data);
    }

    public function tagihan_data(Request $request)
    {
        $data = Tagihan::query()
            ->with('jenjang');

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
                    $html .= "<a href='" . route('jenjang.edit', $row->id) . "' class='btn btn-warning me-1 mb-1'><i class='fas fa-edit'></i> Edit</a>";
                    $html .= "<a href='" . route('jenjang.export', $row->id) . "' class='btn btn-success me-1 mb-1'><i class='fas fa-file-excel'></i> Download Template</a>";
                }

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function tagihan_import(Request $request)
    {
        DB::beginTransaction();
        try {
            Excel::import(new TagihanImport($request->jenjang_id), $request->file('file'));
        } catch (\Exception $e) {
            DB::rollBack();

            $notification = array(
                'message'    => 'Data gagal disimpan',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        }

        DB::commit();

        $notification = array(
            'message'    => 'Data berhasil disimpan',
            'alert-type' => 'success'
        );

        return redirect()->route('tagihan.index')->with($notification);
    }
}
