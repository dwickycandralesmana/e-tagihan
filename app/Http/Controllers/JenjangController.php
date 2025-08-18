<?php

namespace App\Http\Controllers;

use App\Exports\JenjangExport;
use App\Models\Jenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class JenjangController extends BaseController
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
        return view('admin.jenjang.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

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
        $this->jenjang = Jenjang::find($id);

        return view('admin.jenjang.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $jenjang          = Jenjang::find($id);
            $jenjang->nama    = $request->nama;
            $jenjang->catatan = $request->catatan;

            $column = [];

            foreach ($request->key as $key => $value) {
                $column[] = [
                    'key'   => $value,
                    'label' => $request->label[$key],
                ];
            }

            $jenjang->column = json_encode($column);
            $jenjang->save();


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

        return redirect()->route('jenjang.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {}

    public function data(Request $request)
    {
        $data = Jenjang::query();

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
                    // $html .= "<a href='" . route('jenjang.export', $row->id) . "' class='btn btn-success me-1 mb-1'><i class='fas fa-file-excel'></i> Download Template</a>";
                }

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function export($id)
    {
        $jenjang = Jenjang::find($id);

        return Excel::download(new JenjangExport($jenjang), date('YmdHis') . '_template-jenjang-' . $jenjang->nama . '.xlsx');
    }
}
