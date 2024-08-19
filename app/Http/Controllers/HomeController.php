<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailNotification;
use App\Models\Form;
use App\Models\Gender;
use App\Models\Hasil;
use App\Models\Order;
use App\Models\Religion;
use App\Models\RequestPembahasan;
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

    public function index()
    {
        $this->forms = Form::all();

        return view('frontend.home', $this->data);
    }

    public function formulir($slug)
    {
        $this->form      = Form::whereSlug($slug)->first();
        $this->genders   = Gender::all();
        $this->religions = Religion::all();

        return view('frontend.formulir', $this->data);
    }

    public function formulir_store(Request $request, $slug)
    {
        $form = Form::whereSlug($slug)->first();

        if (!$form) {
            $notification = [
                'message' => 'Formulir tidak ditemukan',
                'alert-type' => 'error',
            ];

            return redirect()->route('home')->with($notification);
        }

        $request->validate($this->formValidation['form' . $form->id]);

        DB::beginTransaction();
        try {
            $subtotal      = $form->price;
            $taxPercentage = 0;
            $adminFee      = $form->admin_fee;
            $total         = $subtotal + ($subtotal * $taxPercentage) + $adminFee;
            $refId         = Str::uuid();

            $order                    = new Order();
            $order->code              = date('Ymd') . strtoupper(Str::random(7));
            $order->form_id           = $form->id;
            $order->subtotal          = $subtotal;
            $order->tax               = ($subtotal * $taxPercentage);
            $order->admin_fee         = $adminFee;
            $order->total             = $total;
            $order->payment_reference = $refId;
            $order->nia               = $request->nia;
            $order->name              = $request->name;
            $order->name_ktpa         = $request->name_ktpa;
            $order->gender_id         = $request->gender_id;
            $order->birth_place       = $request->birth_place;
            $order->birth_date        = $request->birth_date;
            $order->religion_id       = $request->religion_id;
            $order->phone             = $request->phone;
            $order->firm_name         = $request->firm_name;
            $order->firm_address      = $request->firm_address;
            $order->firm_phone        = $request->firm_phone;
            $order->address           = $request->address;
            $order->email             = $request->email;
            $order->branch_peradi     = $request->branch_peradi;

            if (isset($request->photo) && $request->photo->isValid()) {
                $photo = $request->photo;
                $photoName = 'person_' . time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads'), $photoName);
                $order->photo = $photoName;
            }

            if (isset($request->photo_ktp) && $request->photo_ktp->isValid()) {
                $photoKtp = $request->photo_ktp;
                $photoKtpName = 'ktp_' . time() . '.' . $photoKtp->getClientOriginalExtension();
                $photoKtp->move(public_path('uploads'), $photoKtpName);
                $order->photo_ktp = $photoKtpName;
            }

            if (isset($request->photo_ktpa) && $request->photo_ktpa->isValid()) {
                $photoKtpa = $request->photo_ktpa;
                $photoKtpaName = 'ktpa_' . time() . '.' . $photoKtpa->getClientOriginalExtension();
                $photoKtpa->move(public_path('uploads'), $photoKtpaName);
                $order->photo_ktpa = $photoKtpaName;
            }

            if (isset($request->photo_license) && $request->photo_license->isValid()) {
                $photoLicense = $request->photo_license;
                $photoLicenseName = 'license_' . time() . '.' . $photoLicense->getClientOriginalExtension();
                $photoLicense->move(public_path('uploads'), $photoLicenseName);
                $order->photo_license = $photoLicenseName;
            }

            $order->tgl_dpc_tujuan    = $request->tgl_dpc_tujuan;
            $order->formulir_diterima = now();
            $order->ketua_dpc_asal    = $request->ketua_dpc_asal;
            $order->ketua_dpc_tujuan  = $request->ketua_dpc_tujuan;
            $order->dpc_asal          = $request->dpc_asal;
            $order->dpc_tujuan        = $request->dpc_tujuan;
            $order->alamat_asal       = $request->alamat_asal;
            $order->alamat_tujuan     = $request->alamat_tujuan;

            if (isset($request->photo_keterangan) && $request->photo_keterangan->isValid()) {
                $photoKeterangan = $request->photo_keterangan;
                $photoKeteranganName = 'keterangan_' . time() . '.' . $photoKeterangan->getClientOriginalExtension();
                $photoKeterangan->move(public_path('uploads'), $photoKeteranganName);
                $order->photo_keterangan = $photoKeteranganName;
            }

            $order->save();

            MidtransConfig::$serverKey             = getSetting('midtrans_server_key');
            MidtransConfig::$clientKey             = getSetting('midtrans_client_key');
            MidtransConfig::$isProduction          = getSetting('midtrans_production_mode') == '0' ? false : true;
            MidtransConfig::$isSanitized           = true;
            MidtransConfig::$is3ds                 = true;
            MidtransConfig::$overrideNotifUrl      = route('midtrans.notification', $refId);
            MidtransConfig::$paymentIdempotencyKey = $refId;
            $product = [];

            $product[] = [
                "id"            => $form->id,
                "price"         => $form->price,
                "quantity"      => 1,
                "name"          => $form->name,
            ];

            $product[] = [
                "id"            => "ADMIN_FEE",
                "price"         => $adminFee,
                "quantity"      => 1,
                "name"          => 'Biaya Admin',
            ];

            $params = array(
                'transaction_details' => array(
                    'order_id' => $refId,
                    'gross_amount' => $form->harga,
                ),
                "customer_details" => array(
                    "first_name" => $request->name,
                    "last_name"  => "",
                    "email"      => $request->email,
                    "phone"      => str_replace(['-', ' ', '+'], '', $request->phone),
                ),
                "expiry" => array(
                    "unit" => "minutes",
                    "duration" => 60 * 24
                ),
                "item_details" => $product,
                'callbacks' => array(
                    'finish'   => route('anggota.formulir.order', $order->id) . '?status=payment',
                    'unfinish' => route('anggota.formulir.order', $order->id) . '?status=payment',
                    'error'    => route('anggota.formulir.order', $order->id) . '?status=payment',
                ),
            );

            $payment    = Snap::createTransaction($params);
            $url        = $payment->redirect_url;
            $order->url = $url;

            $order->save();
        } catch (Exception $e) {
            DB::rollBack();

            $notification = [
                'message' => 'Gagal membuat order, silahkan coba lagi.',
                'alert-type' => 'error',
            ];

            return redirect()->back()->with($notification)->withInput();
        }

        DB::commit();

        return redirect($url);
    }

    public function formulir_order($id)
    {
        $order = Order::with('form')->find($id);

        if (!$order) {
            $notification = [
                'message' => 'Order tidak ditemukan',
                'alert-type' => 'error',
            ];

            return redirect()->route('home')->with($notification);
        }

        $this->order = $order;

        return view('frontend.order', $this->data);
    }

    public function formulir_order_check($id)
    {
        $order = Order::find($id);

        if (!$order) {
            abort(404);
        }

        if ($order->status == "PAID") {
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran sudah diterima!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pembayaran belum diterima!',
        ]);
    }

    public function formulir_order_print($id)
    {
        $order = Order::find($id);

        if (!$order) {
            abort(404);
        }

        $this->order = $order;
        $pdf         = Pdf::loadView('pdf.receipt', $this->data);
        $content     = $pdf->output();
        $name        = 'Bukti Pembayaran_' . date('YmdHis') . '_' . $order->code . '_' . $order->email . '.pdf';
        $path        = public_path('/receipt/' . $name);
        file_put_contents($path, $content);

        return redirect(asset('receipt/' . $name));
    }

    public function midtransNotification(Request $request, $id)
    {
        try {
            MidtransConfig::$serverKey             = getSetting('midtrans_server_key');
            MidtransConfig::$clientKey             = getSetting('midtrans_client_key');
            MidtransConfig::$isProduction          = getSetting('midtrans_production_mode') == '0' ? false : true;

            $payment = (object) Transaction::status($id);
            $fraudStatus = $payment?->fraud_status ?? "accept";

            if (in_array($payment?->transaction_status, ["settlement", "capture"]) && $fraudStatus == "accept") {
                $order = Order::wherePaymentReference($id)->first();
                if ($order->status != 'PAID') {
                    $order->update([
                        'status' => 'PAID',
                        'payment_date' => now(),
                    ]);

                    SendMailNotification::dispatch($order);
                }
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
