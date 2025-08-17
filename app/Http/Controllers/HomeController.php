<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailNotification;
use App\Mail\OtpMail;
use App\Models\Form;
use App\Models\Gender;
use App\Models\Hasil;
use App\Models\HistoryKelas;
use App\Models\Jenjang;
use App\Models\Order;
use App\Models\Otp;
use App\Models\Religion;
use App\Models\RequestPembahasan;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Ujian;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Midtrans\Transaction;
use Yajra\DataTables\Facades\DataTables;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Illuminate\Support\Str;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $this->jenjangs = Jenjang::all();

        $jenjangId = $request->jenjang_id;
        $nis       = $request->nis;

        $siswa       = Siswa::whereNis($nis)->first();
        $tahunAjaran = getDefaultTA();
        $tagihan = HistoryKelas::query()
            ->whereJenjangId($jenjangId)
            ->whereSiswaId($siswa?->id)
            ->whereTahunAjaran($tahunAjaran)
            ->first();

        $this->jenjangId = $jenjangId;
        $this->nis       = $nis;
        $this->tagihan   = $tagihan;

        // $this->otp = Otp::first();
        // return view('emails.otp', $this->data);
        return view('frontend.home', $this->data);
    }

    public function otp(Request $request)
    {
        $response = [
            'status' => false,
            'message' => 'OTP gagal dikirim!',
        ];

        try {
            $user = User::whereEmail($request->email)->first();

            if ($user || 1 == 1) {
                $otp = Otp::updateOrCreate([
                    'email' => $request->email,
                ], [
                    'otp_id' => rand(100000, 999999),
                    'expired_at' => now()->addMinutes(5),
                ]);

                $response['otp_id'] = encryptWithKey($otp->id);
                Mail::to($request->email)->send(new OtpMail($otp));
            }

            $response['message'] = 'Jika email anda terdaftar di sistem, maka anda akan menerima email verifikasi. Silahkan periksa email anda.';
            $response['status']  = true;
        } catch (Exception $e) {
            DB::rollback();

            $response['message'] = $e->getMessage();
        }

        return response()->json($response);
    }
}
