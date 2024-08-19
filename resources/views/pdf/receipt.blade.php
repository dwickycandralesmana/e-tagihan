<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<title>Receipt {{ $order->code }}</title>

		<!-- Favicon -->
		<link rel="icon" href="./images/favicon.png" type="image/x-icon" />

		<!-- Invoice styling -->
		<style>
			body {
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				text-align: center;
				color: #777;
			}

			body h1 {
				font-weight: 300;
				margin-bottom: 0px;
				padding-bottom: 0px;
				color: #000;
			}

			body h3 {
				font-weight: 300;
				margin-top: 10px;
				margin-bottom: 20px;
				font-style: italic;
				color: #555;
			}

			body a {
				color: #06f;
			}

			.invoice-box {
				max-width: 800px;
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				border-collapse: collapse;
			}

			.invoice-box table td {
				/* padding: 5px; */
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				/* padding-bottom: 20px; */
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}
			.align-text-right{
				text-align: right !important; padding-right: 1rem !important;
			}

            .text-center{
                text-align: center !important;
            }

            .text-right{
                text-align: right !important;
            }

            .text-left{
                text-align: left !important;
            }

            .p-1{
                padding: 0.25rem !important;
            }

			.mb-1 {
			margin-bottom: 0.25rem !important; }

			.mb-2 {
			margin-bottom: 0.5rem !important; }

			.mb-3 {
			margin-bottom: 1rem !important; }

			.mb-4 {
			margin-bottom: 2rem !important; }

			.page-break {
				page-break-after: always;
			}

            .table-passenger {
                border-collapse: collapse;
                width: 100%;
            }

            .table-passenger td, .table-passenger th {
                border: 1px solid #ddd;
                padding: 8px;
            }
		</style>
	</head>

	<body>
		<div class="invoice-box">
			<div class="mb-4">
				<img src="{{ $brandLogo }}" style="width: 50%; max-width: 200px">
			</div>

			<table>
				<tr class="top">
					<td colspan="5">
						<table style="color: #181818; font-size: 90%" class="">
							<tr>
								<td>Kode Order</td>
								<td>:</td>
								<td>{{ $order->code }}</td>
								<td>Tanggal Transaksi</td>
								<td>:</td>
								<td>{{ date('j F Y H:i', strtotime($order->created_at)) }}</td>
							</tr>
							<tr>
								<td>Tanggal Pembayaran</td>
								<td>:</td>
								<td>{{ date('j F Y H:i', strtotime($order->payment_date)) }}</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>Metode Pembayaran</td>
								<td>:</td>
								<td colspan="4">
                                    Midtrans - {{ $order->payment_reference }}
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2">
						<table style="color: #181818; font-size: 90%;">
							<tr>
								<td>
                                    <b>Informasi Anggota</b><br />
                                    {{ $order->name }} <br>
                                    {{ $order->phone }} <br>
                                    {{ $order->email }} <br>
								</td>

								<td style="text-align: right; float: right;">
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

            @if($order->status == "CANCELLED")
                <div style="background-color: red; color: white; padding: 1rem; font-weight: bold; font-size: 16px">
                    ORDER INI DIBATALKAN
                </div>
            @elseif($order->status == "REFUND")
                <div style="background-color: black; color: white; padding: 1rem; font-weight: bold; font-size: 16px">
                    ORDER INI DIREFUND
                </div>
            @endif
			<h4 align="left" style="color: #181818;"><b>Order Details</b></h4>
			<table>
				<thead style="color: #181818; font-size: 90%;">
					<th>Qty Product</th>
					<th class="text-center" width="120px">Price</th>
					{{-- <th class="text-center" width="120px">Discount</th> --}}
					<th class="text-center" width="120px">Total</th>
				</thead>
				<tbody style="color: #181818; font-size: 80%;">
                    <tr>
                        <td style="text-align: left;">
                            <span style="font-weight: bold; font-size: 14px">
                                {{ number_format(1) }} x {{ $order->form->name }}
                            </span>
                            <br>
                            <p style="font-size: 90%; margin-top: 1%">
                                <b>
                                    {{ $order->form->name }}
                                </b>
                            </p>
                        </td>
                        <td class="text-center">{{ formatRp($order->subtotal) }}</td>
                        <td style="text-center">{{ formatRp($order->subtotal) }}</td>
                    </tr>

					<tr>
						<td> &nbsp;&nbsp;</td>
						<td class="align-text-right">
							<b>
								Subtotal:
							</b>
						</td>
						<td style="text-align: left;"><b>
							{{ formatRp($order->subtotal) }}
						</td>
					</tr>
                    <tr>
                        <td> &nbsp;&nbsp;</td>
                        <td class="align-text-right">
                            <b>
                                Pajak:
                            </b>
                        </td>
                        <td style="text-align: left;"><b>
                            {{ formatRp($order->tax) }}
                        </td>
                    </tr>
					<tr>
						<td> &nbsp;&nbsp;</td>
						<td class="align-text-right">
							<b>
								Biaya Admin:
							</b>
						</td>
						<td style="text-align: left;"><b>
							{{ formatRp($order->admin_fee) }}
						</td>
					</tr>
					<tr>
						<td> &nbsp;&nbsp;</td>
						<td class="align-text-right">
							<b>
								Grand Total:
							</b>
						</td>
						<td style="text-align: left;"><b>
							{{ formatRp($order->total) }}</b>
						</td>
					</tr>
				</tbody>
			</table>
        </div>
	</body>
</html>
