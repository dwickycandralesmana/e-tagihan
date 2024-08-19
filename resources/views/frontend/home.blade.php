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
                <h3>Cek Tagihan Siswa</h3>
                <form action="#" method="GET">
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold" for="jenjang_id">Jenjang</label>
                        <select class="form-select" id="jenjang_id" name="jenjang_id" required>
                            <option value="">Pilih Jenjang</option>
                            @foreach ($jenjangs as $jenjang)
                                <option value="{{ $jenjang->id }}" @selected($jenjang->id == $jenjangId)>{{ $jenjang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold" for="nis">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" required placeholder="Masukkan NIS" value="{{ $nis }}">
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
                                    <td>{{ $loop->iteration }}</td>
                                    @if($item['key'] == 'tunggakan' && json_decode($tagihan->column, true)[$key+1]['key'] == 'total_tunggakan')
                                        <td>{{ $item['label'] }}</td>
                                        <td>{{ $item['value'] }}</td>

                                        <td>{{ formatRp(json_decode($tagihan->column, true)[$key+1]['value']) }}</td>
                                    @else
                                        <td colspan="2">{{ $item['label'] }}</td>
                                        <td>{{ formatRp($item['value']) }}</td>
                                    @endif
                                </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>

                    <a href="{{ route('tagihan.pdf', $tagihan->id) }}" target="_blank" class="btn btn-primary"><i class="fas fa-file-pdf me-1"></i> Cetak PDF</a>
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
@endsection
