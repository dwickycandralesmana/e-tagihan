@extends('frontend.app')

@section('title', 'Order Status - ' . $order->code)
@section('content')
<div class="container-xl">
    <div class="card card-lg">
        <div class="card-body">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>

            @if($order->status == "PENDING")
                <div class="row">
                    <div class="col-12">
                        <div class="text-center" role="alert">
                            <h3 class="">Pembayaran belum diterima</h3>
                            <p>
                                Harap segera lakukan pembayaran agar formulir Anda dapat diproses.
                            </p>
                            <div class="row d-flex justify-content-center mt-10">
                                <div class="col-auto">
                                    <a href="{{ $order->url }}" class="btn btn-primary">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        Bayar Sekarang
                                    </a>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="text-center" role="alert">
                            <h3 class="">Terima kasih atas pembayaran Anda!</h3>
                            <p>
                                Formulir Anda akan segera kami proses. Anda dapat mencetak bukti pembayaran di bawah ini.
                            </p>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('anggota.formulir.order.print', $order->id) }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-print me-1"></i>
                                    Download Bukti Pembayaran
                                </a>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-6">
                    <a href="{{ route('home') }}">
                        <img src="{{ $brandLogo }}" alt="PERADI" class="img-fluid" style="width: 250px;">
                    </a>
                </div>
                <div class="col-6 text-end">
                    <p class="h3">{{ $order->name }}</p>
                    <address>
                        {{ $order->address }}<br>
                        {{ $order->email }}
                    </address>
                </div>
                <div class="col-12 my-5">
                    <h1>Invoice #{{ $order->code }}</h1>
                </div>
            </div>
            <table class="table table-transparent table-responsive">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 1%"></th>
                        <th>Product</th>
                        <th class="text-center" style="width: 1%">Qnt</th>
                        <th class="text-end" style="width: 1%">Unit</th>
                        <th class="text-end" style="width: 1%">Amount</th>
                    </tr>
                </thead>
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        <p class="strong mb-1">{{ $order->form->name }}</p>
                    </td>
                    <td class="text-center">
                        1
                    </td>
                    <td class="text-end text-nowrap">{{ formatRp($order->subtotal) }}</td>
                    <td class="text-end text-nowrap">{{ formatRp($order->subtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="strong text-end">Subtotal</td>
                    <td class="text-end">{{ formatRp($order->subtotal) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="strong text-end">Pajak</td>
                    <td class="text-end">{{ formatRp($order->tax) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="strong text-end">Biaya Admin</td>
                    <td class="text-end">{{ formatRp($order->admin_fee) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="strong text-uppercase text-end">Grand Total</td>
                    <td class="font-weight-bold text-end">{{ formatRp($order->total) }}</td>
                </tr>
            </table>
            <p class="text-muted text-center mt-5">
                Terima kasih telah melakukan pembayaran. Formulir Anda akan segera kami proses setelah pembayaran Anda kami verifikasi.
            </p>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function(){
        @if($order->status == "PENDING")
            setInterval(() => {
                $.ajax({
                    url: "{{ route('anggota.formulir.order.check', $order->id) }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if(response.success){
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Diterima',
                                text: 'Pembayaran Anda telah diterima. Formulir Anda akan segera kami proses.',
                            });

                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    },
                });
            }, 5000);
        @endif
    })
</script>
@endsection
