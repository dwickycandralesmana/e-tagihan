@extends('frontend.app')

@section('title', 'Aplikasi Anggota PERADI')

@section('content')
    <div class="container-xl mt-3">
        <div class="row">
            <div class="col-md-12 text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ $brandLogo }}" alt="PERADI" class="img-fluid" style="width: 250px;">
                </a>
            </div>
            <div class="col-12 text-center mt-5 fw-bold">
                <h2>PILIH FORMULIR DI BAWAH INI</h2>

                @forelse ($forms as $item)
                    <div class="bg-primary w-100 my-4 p-3 text-white text-center">
                        <a href="{{ route('anggota.formulir', $item->slug) }}" class="text-white">
                            {{ $item->name }}
                        </a>
                    </div>
                @empty

                @endforelse

                <div>
                    Supported by
                </div>
                <img src="{{ asset('assets/img/payment-gateway.png') }}" alt="Logo" class="img-fluid" style="max-width: 600px; width: 100%;">
            </div>
        </div>
    </div>
@endsection
