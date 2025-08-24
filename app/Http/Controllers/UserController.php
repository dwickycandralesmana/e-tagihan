<?php

namespace App\Http\Controllers;

use App\Imports\UserImport;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UserController extends BaseController
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
        return view('admin.user.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.user.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required',
            'password_confirmation' => 'required|same:password',
            'nomor_hp'              => 'required',
            'alamat'                => 'required',
            'tempat_lahir'          => 'required',
            'tanggal_lahir'         => 'required',
            'jenis_kelamin'         => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user                = new User();
            $user->name          = $request->name;
            $user->email         = $request->email;
            $user->password      = bcrypt($request->password);
            $user->nomor_hp      = $request->nomor_hp;
            $user->alamat        = $request->alamat;
            $user->tempat_lahir  = $request->tempat_lahir;
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->type          = 'user';
            $user->save();

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

        return redirect()->route('user.index')->with($notification);
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
        $this->user = User::find($id);

        return view('admin.user.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required|email|unique:users,email,' . $id,
            'nomor_hp'              => 'required',
            'alamat'                => 'required',
            'tempat_lahir'          => 'required',
            'tanggal_lahir'         => 'required',
            'jenis_kelamin'         => 'required',
        ]);

        if ($request->password) {
            $request->validate([
                'password'              => 'required',
                'password_confirmation' => 'required|same:password',
            ]);
        }

        DB::beginTransaction();
        try {
            $user                = User::find($id);
            $user->name          = $request->name;
            $user->email         = $request->email;

            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            $user->nomor_hp      = $request->nomor_hp;
            $user->alamat        = $request->alamat;
            $user->tempat_lahir  = $request->tempat_lahir;
            $user->tanggal_lahir = $request->tanggal_lahir;
            $user->jenis_kelamin = $request->jenis_kelamin;
            $user->type          = 'user';
            $user->save();

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

        return redirect()->route('user.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
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

    public function data(Request $request)
    {
        $data = User::query()
            ->whereType('bendahara');

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('tanggal_lahir', function ($row) {
                return date('j F Y', strtotime($row->tanggal_lahir));
            })
            ->addColumn('action', function ($row) {
                $html = "";

                if (auth()->user()) {
                    $html .= "<a href='" . route('user.edit', $row->id) . "' class='btn btn-warning me-1 mb-1'><i class='fas fa-edit'></i> Edit</a>";
                    $html .= "<button class='btn btn-danger btn-delete mb-1 me-1' data-id='" . $row->id . "'><i class='fas fa-trash'></i> Hapus</button>";
                }

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function profile()
    {
        $this->user = User::find(Auth::user()->id);

        return view('admin.user.profile', $this->data);
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
            $user                = User::find($id);
            $user->name          = $request->name;
            $user->email         = $request->email;

            if ($request->password) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

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
