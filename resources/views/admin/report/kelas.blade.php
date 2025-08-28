@extends('layouts.app')

@section('title')
Tagihan per Siswa
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Laporan
                </div>
                <h2 class="page-title">
                    Tagihan per Siswa
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laporan.kelas.export') }}" method="get">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="tahun_ajaran" class="fw-bold">Tahun Pelajaran</label>
                                <select name="tahun_ajaran" id="tahun_ajaran" class="form-control select2">
                                    <option value="">-- Pilih Tahun Pelajaran --</option>
                                    @for ($i = date('Y'); $i >= 2019; $i--)
                                        <option value="{{ $i }}" @if ($i == getDefaultTA()) selected @endif>{{ $i }}/{{ $i + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="kelas" class="fw-bold">Kelas</label>
                                <select name="kelas" id="kelas" class="form-control select2">
                                    @foreach ($listKelas as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn btn-success mt-3 float-end"><i class="fas fa-file-excel"></i> Export</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liLaporanKelas').addClass('active');
        $('#liLaporan').addClass('active');
        $('#liLaporan .dropdown-menu').addClass('show');
        $('.select2').select2();
    });
</script>
@endsection
