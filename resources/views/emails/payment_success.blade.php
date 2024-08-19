<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <title>

    </title>
    <!--[if !mso]><!-- -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        #outlook a {
            padding: 0;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass * {
            line-height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        p {
            display: block;
            margin: 13px 0;
        }

    </style>
    <!--[if !mso]><!-->
    <style type="text/css">
        @media only screen and (max-width:480px) {
            @-ms-viewport {
                width: 320px;
            }

            @viewport {
                width: 320px;
            }
        }

    </style>
    <style type="text/css">
        @media only screen and (min-width:480px) {
            .mj-column-per-100 {
                width: 100% !important;
            }
        }

    </style>


    <style type="text/css">
    </style>

</head>

<body style="background-color:#f9f9f9;">
    <div style="background-color:#f9f9f9;">
        <div style="background:#f9f9f9;background-color:#f9f9f9;Margin:0px auto;max-width:600px;">

            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                style="background:#f9f9f9;background-color:#f9f9f9;width:100%;">
                <tbody>
                    <tr>
                        <td
                            style="border-bottom:#333957 solid 5px;direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div style="background:#fff;background-color:#fff;Margin:0px auto;max-width:600px;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation"
                style="background:#fff;background-color:#fff;width:100%;">
                <tbody>
                    <tr>
                        <td
                            style="border:#dddddd solid 1px;border-top:0px;direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
                            <div class="mj-column-per-100 outlook-group-fix"
                                style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:bottom;width:100%;">

                                <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                    style="vertical-align:bottom;" width="100%">

                                    <tr>
                                        <td align="center"
                                            style="font-size:0px;padding:10px 25px;word-break:break-word;">

                                            <table align="center" border="0" cellpadding="0" cellspacing="0"
                                                role="presentation"
                                                style="border-collapse:collapse;border-spacing:0px;">
                                                <tbody>
                                                    <tr>
                                                        <td style="width:200px;">

                                                            <img height="auto" src="{{ asset('assets/img/logo.webp') }}"
                                                                style="border:0;display:block;outline:none;text-decoration:none;width:100%;"
                                                                width="200" />

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="center"
                                            style="font-size:0px;padding:10px 25px;word-break:break-word;">

                                            <div
                                                style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:24px;font-weight:bold;line-height:22px;text-align:center;color:#525252;">
                                                Thank you for your order
                                            </div>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">

                                            <div
                                                style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:16px;line-height:22px;text-align:left;color:#525252; text-align: center;">
                                                <p style="font-weight: bold;">
                                                    Halo {{ $order->name }},
                                                </p>

                                                <p>
                                                    Terima kasih telah melakukan transaksi di <a href="{{ route('home') }}" style="text-decoration: none;" target="_blank">{{ config('app.name') }}</a>. Berikut adalah rincian transaksi Anda.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="left" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                                            <table 0="[object Object]" 1="[object Object]" 2="[object Object]"
                                                border="0"
                                                style="cellspacing:0;color:#000;font-family:'Helvetica Neue',Arial,sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%; margin-bottom: 20px;">
                                                <tr style="border-bottom:1px solid #ecedee;text-align:left;">
                                                    <th colspan="6" style="text-wrap: nowrap; text-align: center; font-size: 15px;">Informasi Order</th>
                                                </tr>
                                                <tr style="text-align:left;">
                                                    <th>Kode Order</th>
                                                    <th width="1%">:</th>
                                                    <th>{{ $order->code }}</th>
                                                    <th>Nama</th>
                                                    <th width="1%">:</th>
                                                    <th>{{ $order->name }}</th>
                                                </tr>
                                                <tr style="text-align:left;">
                                                    <th>Tanggal Transaksi</th>
                                                    <th width="1%">:</th>
                                                    <th>{{ $order->created_at->format('d F Y') }}</th>
                                                    <th>Nomor HP</th>
                                                    <th width="1%">:</th>
                                                    <th>{{ $order->phone }}</th>
                                                </tr>
                                                <tr style="border-bottom:1px solid #ecedee;text-align:left;">
                                                    <th>Metode Pembayaran</th>
                                                    <th>:</th>
                                                    <th colspan="4">Midtrans - {{ $order->payment_reference }}</th>
                                                </tr>
                                            </table>
                                            <table 0="[object Object]" 1="[object Object]" 2="[object Object]"
                                                border="0"
                                                style="cellspacing:0;color:#000;font-family:'Helvetica Neue',Arial,sans-serif;font-size:13px;line-height:22px;table-layout:auto;width:100%;">
                                                <tr style="border-bottom:1px solid #ecedee;text-align:left;">
                                                    <th colspan="6" style="text-wrap: nowrap; text-align: center; font-size: 15px;">Detail Order</th>
                                                </tr>
                                                <tr style="border-bottom:1px solid #ecedee;text-align:left;">
                                                    <th style="padding: 0 15px 10px 0;">Item</th>
                                                    <th style="padding: 0 15px; text-wrap: nowrap; width: 50px;">Qty</th>
                                                    <th style="padding: 0 0 0 15px; text-wrap: nowrap; width: 100px;" align="right">Total</th>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 5px 15px 5px 0;">
                                                        <span style="font-weight: bold; font-size: 15px;">
                                                            {{ $order->form->name }}
                                                        </span>
                                                    </td>
                                                    <td style="padding: 0px 15px;">1</td>
                                                    <td style="padding: 0 0 0 15px; text-wrap: nowrap;" align="right">{{ formatRp($order->subtotal) }}</td>
                                                </tr>
                                                <tr
                                                    style="border-top:2px solid #ecedee;text-align:left;padding:15px 0;">
                                                    <td style="padding: 5px 15px 5px 0; font-weight:bold" colspan="2">SUBTOTAL</td>
                                                    <td style="padding: 0 0 0 15px; font-weight:bold;  text-wrap: nowrap;" align="right">
                                                        {{ formatRp($order->subtotal) }}
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="text-align:left;padding:15px 0;">
                                                    <td style="padding: 5px 15px 5px 0; font-weight:bold" colspan="2">PAJAK</td>
                                                    <td style="padding: 0 0 0 15px; font-weight:bold;  text-wrap: nowrap;" align="right">
                                                        {{ formatRp($order->tax) }}
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="text-align:left;padding:15px 0;">
                                                    <td style="padding: 5px 15px 5px 0; font-weight:bold" colspan="2">BIAYA ADMIN</td>
                                                    <td style="padding: 0 0 0 15px; font-weight:bold;  text-wrap: nowrap;" align="right">
                                                        {{ formatRp($order->admin_fee) }}
                                                    </td>
                                                </tr>
                                                <tr
                                                    style="border-bottom:2px solid #ecedee;text-align:left;padding:15px 0;">
                                                    <td style="padding: 5px 15px 5px 0; font-weight:bold" colspan="2">TOTAL</td>
                                                    <td style="padding: 0 0 0 15px; font-weight:bold; text-wrap: nowrap;" align="right">
                                                        {{ formatRp($order->total) }}
                                                    </td>
                                                </tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <div style="Margin:0px auto;max-width:600px;">

            <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="width:100%;">
                <tbody>
                    <tr>
                        <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;">
                            <div class="mj-column-per-100 outlook-group-fix"
                                style="font-size:13px;text-align:left;direction:ltr;display:inline-block;vertical-align:bottom;width:100%;">

                                <table border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
                                    <tbody>
                                        <tr>
                                            <td style="vertical-align:bottom;padding:0;">

                                                <table border="0" cellpadding="0" cellspacing="0" role="presentation"
                                                    width="100%">

                                                    <tr>
                                                        <td align="center"
                                                            style="font-size:0px;padding:0;word-break:break-word;">

                                                            <div
                                                                style="font-family:'Helvetica Neue',Arial,sans-serif;font-size:12px;font-weight:300;line-height:1;text-align:center;color:#575757;">
                                                                <a href="{{ route('home') }}" style="color:#575757" target="_blank">{{ config('app.name') }}</a>
                                                            </div>

                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</body>

</html>
