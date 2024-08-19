<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailNotification;
use App\Models\Form;
use App\Models\Gender;
use App\Models\Hasil;
use App\Models\Jenjang;
use App\Models\Order;
use App\Models\Religion;
use App\Models\RequestPembahasan;
use App\Models\Tagihan;
use App\Models\Ujian;
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

        $tagihan = Tagihan::whereJenjangId($jenjangId)->whereNis($nis)->first();

        $this->jenjangId = $jenjangId;
        $this->nis       = $nis;
        $this->tagihan   = $tagihan;

        return view('frontend.home', $this->data);
    }
}
