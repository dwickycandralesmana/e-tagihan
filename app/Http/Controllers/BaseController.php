<?php

namespace App\Http\Controllers;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $data = [];

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->data[$name];
    }

    public function __isset($name)
    {
        return isset($this->data[$name]);
    }

    public function __construct()
    {
        $this->auth = User::find(auth()->user()?->id);
        $this->brandLogo = asset('assets/img/' . getSetting('brand_logo'));

        $formValidation = [];

        $form1 = [
            'nia'           => 'required',
            'name'          => 'required',
            'name_ktpa'     => 'required',
            'gender_id'     => 'required',
            'birth_place'   => 'required',
            'birth_date'    => 'required',
            'religion_id'   => 'required',
            'phone'         => 'required',
            'firm_name'     => 'required',
            'firm_address'  => 'required',
            'firm_phone'    => 'required',
            'address'       => 'required',
            'email'         => 'required',
            'branch_peradi' => 'required',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
            'photo_ktp'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
            'photo_ktpa'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
            'photo_license' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ];

        $form2 = [
            'nia'               => 'required',
            'name'              => 'required',
            'email'             => 'required',
            'phone'             => 'required',
            // 'formulir_diterima' => 'required',
            'ketua_dpc_asal'    => 'required',
            'ketua_dpc_tujuan'  => 'required',
            'dpc_asal'          => 'required',
            'dpc_tujuan'        => 'required',
            'tgl_dpc_tujuan'    => 'required',
            'alamat_asal'       => 'required',
            'alamat_tujuan'     => 'required',
            'photo_keterangan'  => 'required',
        ];

        $form3 = [
            'nia'   => 'required',
            'name'  => 'required',
            'email' => 'required',
            'phone' => 'required',
        ];

        $this->textArray = [
            'nia'               => 'NIA (Nomor Induk Advokat)',
            'name'              => 'Nama Lengkap & Gelar',
            'name_ktpa'         => 'Nama sesuai KTPA',
            'email'             => 'Email',
            'phone'             => 'No. HP',
            'birth_place'       => 'Tempat Lahir',
            'birth_date'        => 'Tanggal Lahir',
            'religion_id'       => 'Agama',
            'gender_id'         => 'Jenis Kelamin',
            'address'           => 'Alamat',
            'firm_name'         => 'Nama Kantor',
            'firm_address'      => 'Alamat Kantor',
            'firm_phone'        => 'No. Telp Kantor',
            'branch_peradi'     => 'Cabang PERADI',
            'photo'             => 'Foto',
            'photo_ktp'         => 'Foto KTP',
            'photo_keterangan'  => 'Foto Keterangan',
            'photo_ktpa'        => 'Foto KTPA',
            'photo_license'     => 'Foto Ijazah',
            'ketua_dpc_asal'    => 'Ketua DPC Asal',
            'ketua_dpc_tujuan'  => 'Ketua DPC Tujuan',
            'dpc_asal'          => 'DPC Asal',
            'dpc_tujuan'        => 'Mohon Pindah ke DPC',
            'tgl_dpc_tujuan'    => 'Tanggal Pindah Domisili',
            'alamat_asal'       => 'Alamat Asal',
            'alamat_tujuan'     => 'Alamat Tujuan',
            'formulir_diterima' => 'Formulir Pindah Diterima',
        ];

        $formValidation['form1'] = $form1;
        $formValidation['form2'] = $form2;
        $formValidation['form3'] = $form3;

        $this->formValidation = $formValidation;
    }

    public static function middleware(): array
    {
        return [
            function (Request $request, Closure $next) {
                return $next($request);
            },
        ];
    }
}
