<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\Siswa;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends BaseController
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
        return view('admin.siswa.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.siswa.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nis'  => 'required|unique:siswas,nis',
        ]);

        DB::beginTransaction();
        try {
            $siswa       = new Siswa();
            $siswa->nama = $request->nama;
            $siswa->nis  = $request->nis;
            $siswa->save();

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

        return redirect()->route('siswa.index')->with($notification);
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
        $this->user = Siswa::find($id);

        return view('admin.siswa.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'nis'  => 'required|unique:siswas,nis,' . $id,
        ]);

        DB::beginTransaction();
        try {
            $siswa                = Siswa::find($id);
            $siswa->nama          = $request->nama;
            $siswa->nis           = $request->nis;
            $siswa->save();

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

        return redirect()->route('siswa.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();
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
        $data = Siswa::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal_lahir', function ($row) {
                return date('j F Y', strtotime($row->tanggal_lahir));
            })
            ->addColumn('action', function ($row) {
                $html = "";

                if (auth()->user()) {
                    $html .= "<a href='" . route('siswa.edit', $row->id) . "' class='btn btn-warning me-1 mb-1'><i class='fas fa-edit'></i> Edit</a>";
                    $html .= "<button class='btn btn-danger btn-delete mb-1 me-1' data-id='" . $row->id . "'><i class='fas fa-trash'></i> Hapus</button>";
                }

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function profile()
    {
        $this->user = Siswa::find(Auth::user()->id);

        return view('admin.siswa.profile', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function profile_update(Request $request)
    {
        $id = Auth::user()->id;

        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users,email,' . $id,
        ]);

        if ($request->password) {
            $request->validate([
                'password'              => 'required',
                'password_confirmation' => 'required|same:password',
            ]);
        }

        DB::beginTransaction();
        try {
            $siswa                = Siswa::find($id);
            $siswa->name          = $request->name;
            $siswa->email         = $request->email;

            if ($request->password) {
                $siswa->password = bcrypt($request->password);
            }

            $siswa->save();

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

        return redirect()->route('dashboard')->with($notification);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        DB::beginTransaction();

        try {
            Excel::import(new UserImport, $request->file('file'));
        } catch (Exception $e) {
            DB::rollback();

            $notification = array(
                'message'    => $e->getMessage(),
                'alert-type' => 'danger',
                'status'     => false,
            );

            return redirect()->back()->with($notification);
        }

        DB::commit();

        $notification = array(
            'status' => true,
            'alert-type' => 'success',
            'message' => 'Data user berhasil diimport!',
        );

        return redirect()->back()->with($notification);
    }
}
