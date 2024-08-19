<?php

namespace App\Http\Controllers;

use App\Imports\TagihanImport;
use App\Models\Jenjang;
use App\Models\Tagihan;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TagihanController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->jenjang = Jenjang::all();

        return view('admin.tagihan.index', $this->data);
    }

    public function data(Request $request)
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
                    $html .= "<a href='" . route('tagihan.show', $row->id) . "' class='btn btn-primary me-1 mb-1'><i class='fas fa-eye'></i> Detail</a>";
                    $html .= "<a href='" . route('tagihan.pdf', $row->id) . "' target='_blank' class='btn btn-success me-1 mb-1'><i class='fas fa-file-pdf'></i> Download Kartu Kendali</a>";
                    $html .= "<button class='btn btn-danger btn-delete mb-1 me-1' data-id='" . $row->id . "'><i class='fas fa-trash'></i> Hapus</button>";
                }

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function import(Request $request)
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

    public function show($id)
    {
        $this->tagihan = Tagihan::findOrFail($id);

        return view('admin.tagihan.show', $this->data);
    }

    public function pdf($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $pdf = Pdf::loadView('pdf.tagihan', compact('tagihan'));

        return $pdf->stream('Kartu Kendali - ' . $tagihan->nama . '.pdf');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = Tagihan::findOrFail($id);
            $user->delete();
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
}
