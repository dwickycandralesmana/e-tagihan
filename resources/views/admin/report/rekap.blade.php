@extends('layouts.app')

@section('title')
Rekap Petugas
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
                    Rekap Petugas
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laporan.rekap') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="tanggal_pembayaran" class="fw-bold">Periode</label>
                                <input type="text" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control daterangepicer" value="{{ \Carbon\Carbon::now()->subDays(7)->format('d/m/Y') . ' - ' . \Carbon\Carbon::now()->format('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="bendahara" class="fw-bold">Bendahara</label>
                                <select name="bendahara" id="bendahara" class="form-control select2">
                                    @foreach ($bendahara as $item)
                                        <option value="{{ $item->id }}" @selected($item->id == request('bendahara'))>{{ $item->name }}</option>
                                    @endforeach
                                </select>

                                <button type="button" class="btn btn-success mt-3 float-end" id="btnExport"><i class="fa fa-file-excel"></i> Export</button>
                                <button type="submit" class="btn btn-primary mt-3 float-end me-1" id="btnFilter"><i class="fa fa-filter"></i> Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-12">
                        @if(isset($detail))
                            <div class="table-responsive">
                                <table id="table" class="table table-collapsed">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nomor</th>
                                            <th>Tanggal</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Nama Pembayaran</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($detail as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->pembayaran->code }}</td>
                                                <td>{{ $item->tanggal_pembayaran }}</td>
                                                <td>{{ $item->pembayaran->siswa->nama }}</td>
                                                <td>{{ $item->historyKelas->kelas }}</td>
                                                <td>{{ $item->tipe_tagihan . ' ' . $item->bulan_text }}</td>
                                                <td>{{ formatRp($item->bayar) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Data Tidak Ditemukan</td>
                                            </tr>
                                        @endforelse

                                        @if($detail->count() > 0)
                                            @php
                                                $groupedByTagihan = $detail->groupBy('tipe_tagihan_id');
                                            @endphp

                                            @foreach($groupedByTagihan as $tagihan)
                                                <tr>
                                                    <td colspan="6" class="text-end">{{ $tagihan->first()->tipe_tagihan }}</td>
                                                    <td>{{ formatRp($tagihan->sum('bayar')) }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="6" class="text-end">Total</td>
                                                <td>{{ formatRp($detail->sum('bayar')) }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liLaporanPotongan').addClass('active');
        $('#liLaporan').addClass('active');
        $('#liLaporan .dropdown-menu').addClass('show');
        $('.select2').select2();

        $('.daterangepicer').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            },
            ranges: {
                'Hari ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Dua Hari Lalu': [moment().subtract(2, 'days'), moment().subtract(1, 'days')],
                'Minggu Ini': [moment().startOf('week'), moment().endOf('week')],
                'Dua Minggu Terakhir': [moment().subtract(2, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Dua Bulan Terakhir': [moment().subtract(2, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            }
        });

        $(document).on('click','#btnExport', function(){
            var tanggal_pembayaran = $('#tanggal_pembayaran').val();
            var bendahara = $('#bendahara').val();

            window.location.href = "{{ route('laporan.rekap.export') }}?tanggal_pembayaran=" + tanggal_pembayaran + "&bendahara=" + bendahara;
        })
    });
</script>
@endsection
