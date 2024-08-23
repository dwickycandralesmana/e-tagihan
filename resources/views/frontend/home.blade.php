@extends('frontend.app')

@section('title', 'Cek Tagihan')

@section('content')
    <div class="container-xl mt-3">
        <div class="row" style="max-width: 800px; margin: 0 auto;">
            <div class="col-md-12 text-center">
                <a href="{{ route('home') }}">
                    <img src="{{ $brandLogo }}" alt="PERADI" class="img-fluid" style="width: 150px;">
                </a>
            </div>
            <div class="col-12 text-center mt-5 fw-bold">
                <h1>Selamat Datang di Aplikasi <br>{{ env('APP_NAME') }}</h1>
            </div>

            <div class="col-12 text-center mt-3">
                <h3>
                    Cek Tagihan Siswa<br>
                    <small class="text-muted">Silahkan masukkan NIS siswa dan pilih jenjang untuk mengecek tagihan</small>

                </h3>
                <form action="#" method="GET">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold" for="jenjang_id">Jenjang</label>
                                <select class="form-select" id="jenjang_id" name="jenjang_id" required>
                                    <option value="">Pilih Jenjang</option>
                                    @foreach ($jenjangs as $jenjang)
                                        <option value="{{ $jenjang->id }}" @selected($jenjang->id == $jenjangId)>{{ $jenjang->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold" for="nis">NIS</label>
                                <input type="text" class="form-control" id="nis" name="nis" required placeholder="Masukkan NIS" value="{{ $nis }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>
                        Cek Tagihan
                    </button>
                </form>
            </div>

            @if(isset($tagihan))
                <div class="col-12 mt-3">
                    <h3>
                        RINCIAN KEKURANGAN KEUANGAN SISWA
                    </h3>
                    <table>
                        <tr>
                            <td width="50">Nama</td>
                            <td>:</td>
                            <td>{{ $tagihan->nama }}</td>
                        </tr>
                        <tr>
                            <td width="50">NIS</td>
                            <td>:</td>
                            <td>{{ $tagihan->nis }}</td>
                        </tr>
                        <tr>
                            <td width="50">Kelas</td>
                            <td>:</td>
                            <td>{{ $tagihan->kelas }}</td>
                        </tr>
                    </table>
                    <Br>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <th>No</th>
                            <th colspan="2">Rincian</th>
                            <th>Jumlah</th>
                        </thead>
                        <tbody>
                            @forelse(json_decode($tagihan->column, true) ?? [] as $key => $item)
                                @php
                                    if($item['key'] == 'total_tunggakan' && json_decode($tagihan->column, true)[$key-1]['key'] == 'tunggakan'){
                                        continue;
                                    }
                                @endphp

                                <tr>
                                    @if($loop->last)
                                        <td></td>
                                    @else
                                        <td>{{ $loop->iteration }}</td>
                                    @endif

                                    @if($item['key'] == 'tunggakan' && json_decode($tagihan->column, true)[$key+1]['key'] == 'total_tunggakan')
                                        <td class="">{{ $item['label'] }}</td>
                                        <td>{{ $item['value'] }}</td>

                                        <td class="">{{ formatRp(json_decode($tagihan->column, true)[$key+1]['value']) }}</td>
                                    @else
                                        @if($loop->last)
                                            <td colspan="2" class="fw-bold text-center">{{ $item['label'] }}</td>
                                            <td class="text-nowrap fw-bold">{{ formatRp($item['value']) }}</td>
                                        @else
                                            <td colspan="2" class="">{{ $item['label'] }}</td>
                                            <td class="text-nowrap">{{ formatRp($item['value']) }}</td>
                                        @endif
                                    @endif
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>

                    <a href="{{ route('tagihan.pdf', $tagihan->id) }}" target="_blank" class="btn btn-primary"><i class="fas fa-file-pdf me-1"></i> Download Kartu Kendali</a>

                    <h3 class="mt-3">
                        Keterangan
                    </h3>
                    {!! $tagihan->jenjang->catatan !!}
                    <div id="ttd">
                        <table width="100%">
                            <tr>
                                <td width="70%"></td>
                                <td width="30%" class="text-center">
                                    <div>
                                        Karanganyar, {{ date('d F Y') }} <Br>
                                        Bendahara Sekolah,
                                    </div>
                                    <div class="my-2">
                                        <img src="{{ asset('assets/img/ttd.png') }}" alt="ttd" width="100" height="100">
                                    </div>
                                    <div id="camat">
                                        <strong><u>Najmudin, S.Kom</u></strong>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            @elseif($nis != '')
                <div class="col-12 text-center mt-3">
                    {{-- //alert --}}
                    <div class="alert alert-danger" role="alert">
                        Data tagihan tidak ditemukan
                    </div>
                </div>
            @endif
        </div>
    </div>

    <footer class="footer footer-transparent d-print-none">
        <div class="container">
            <div class="row text-center align-items-center">
                <div class="col-lg-12">
                    <b>
                        MENU CEPAT
                    </b>
                    <ul class="nav nav-footer align-items-center justify-content-center">
                        <li class="nav-item">
                            <a href="https://linktr.ee/adminpenda2" target="_blank" class="nav-link" target="_blank">Cek NIS Siswa</a>
                        </li>
                        <li class="nav-item">
                            <a href="https://linktr.ee/adminpenda2kra" target="_blank" class="nav-link" target="_blank">Rekapan Wali Kelas</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
@endsection
