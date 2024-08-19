<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends BaseController
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
        $this->settings = Setting::all();

        return view('admin.setting.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        $this->setting = Setting::find($id);

        return view('admin.setting.edit', $this->data);
    }

    public function bulk(Request $request)
    {
        DB::beginTransaction();
        try {
            $settings = Setting::all();
            foreach ($settings as $setting) {
                if (!isset($request->{$setting->key})) {
                    continue;
                }

                if (str_contains($setting->key, 'logo')) {
                    if ($request->hasFile($setting->key)) {
                        $file = $request->file($setting->key);
                        $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
                        $file->move(public_path('assets/img/'), $filename);
                        $setting->value = $filename;
                    }
                } else {
                    $setting->value = $request->{$setting->key};
                }

                $setting->save();
            }

            $notification = array(
                'message'    => 'Data berhasil disimpan',
                'alert-type' => 'success'
            );
        } catch (\Exception $e) {
            $notification = array(
                'message'    => $e->getMessage(),
                'alert-type' => 'error'
            );

            DB::rollBack();

            return redirect()->back()->with($notification);
        }

        DB::commit();

        return redirect()->route('setting.index')->with($notification);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'value' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $setting = Setting::find($id);
            $setting->value = $request->value;
            $setting->save();

            $notification = array(
                'message'    => 'Data berhasil disimpan',
                'alert-type' => 'success'
            );
        } catch (\Exception $e) {
            $notification = array(
                'message'    => $e->getMessage(),
                'alert-type' => 'error'
            );

            DB::rollBack();

            return redirect()->back()->with($notification);
        }

        DB::commit();

        return redirect()->route('setting.index')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }

    public function data(Request $request)
    {
        $data = Setting::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $html = "";

                if (auth()->user()) {
                    $html .= "<a href='" . route('setting.edit', $row->id) . "' class='btn btn-warning me-1 mb-1'><i class='fas fa-edit'></i> Edit</a>";
                }

                return $html;
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}
