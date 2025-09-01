@extends('layouts.app')

@section('title')
Detail Pembayaran
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
                    Detail Pembayaran
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laporan.pembayaran.export') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="tahun_ajaran" class="fw-bold">Tahun Pelajaran</label>
                                <select name="tahun_ajaran" id="tahun_ajaran" class="form-control select2">
                                    <option value="">-- Pilih Tahun Pelajaran --</option>
                                    @for ($i = date('Y'); $i >= 2019; $i--)
                                        <option value="{{ $i }}" @if ($i == getDefaultTA()) selected @endif>{{ $i }}/{{ $i + 1 }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="kelas" class="fw-bold">Kelas</label>
                                <select name="kelas" id="kelas" class="form-control select2">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach ($listKelas as $item)
                                        <option value="{{ $item->kelas }}" data-jenjang="{{ $item->jenjang_id }}">{{ $item->kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="jenis_pembayaran" class="fw-bold">Jenis Pembayaran</label>
                                <select name="jenis_pembayaran" id="jenis_pembayaran" class="form-control select2">
                                    <option value="">-- Semua Jenis Pembayaran --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="tanggal_pembayaran" class="fw-bold">Tanggal Transaksi</label>
                                <input type="text" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control daterangepicer" value="{{ \Carbon\Carbon::now()->subDays(7)->format('d/m/Y') . ' - ' . \Carbon\Carbon::now()->format('d/m/Y') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-group mb-3">
                                <label for="bendahara" class="fw-bold">Bendahara</label>
                                <select name="bendahara" id="bendahara" class="form-control select2">
                                    <option value="">-- Semua Bendahara --</option>
                                    @foreach ($bendahara as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn btn-success mt-3 float-end" id="btnExport"><i class="fa fa-file-excel"></i> Export</button>
                                <button type="button" class="btn btn-primary mt-3 float-end me-1" id="btnFilter"><i class="fa fa-filter"></i> Filter</button>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="table-responsive">
                                <table id="table" class="table table-striped" style="width: 100%">
                                    <thead>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Tahun Ajaran</th>
                                        <th>Kelas</th>
                                        <th>Siswa</th>
                                        <th>Jenis Pembayaran</th>
                                        <th>Bayar</th>
                                        <th>Potongan</th>
                                        <th>Total</th>
                                        <th>Dibuat Oleh</th>
                                        <th>Aksi</th>
                                    </thead>
                                </table>
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
        $('#liLaporanPembayaran').addClass('active');
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

        var jenjangId = $('#kelas').find('option:selected').data('jenjang');

        let table = $("#table").DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [{
                defaultContent: "-",
                targets       : "_all"
            }],
            ajax: {
                url: "{{ route('laporan.pembayaran.data') }}",
                data: function (d) {
                    d.tahun_ajaran = $('#tahun_ajaran').val();
                    d.kelas = $('#kelas').val();
                    d.jenis_pembayaran = $('#jenis_pembayaran').val();
                    d.tanggal_pembayaran = $('#tanggal_pembayaran').val();
                    d.bendahara = $('#bendahara').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
                {data: 'tanggal_pembayaran', name: 'tanggal_pembayaran', className: 'text-start'},
                {data: 'tahun_ajaran', name: 'tahun_ajaran'},
                {data: 'nama_kelas', name: 'history_kelas.kelas'},
                {data: 'nama', name: 'nama'},
                {data: 'tipe_tagihan', name: 'tipe_tagihans.nama'},
                {data: 'bayar', name: 'bayar'},
                {data: 'potongan', name: 'potongan'},
                {data: 'jumlah', name: 'jumlah'},
                {data: 'created_by', name: 'users.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '15%'},
            ],
        });

        $(document).on('click','#btnFilter', function(){
            table.ajax.reload();
        })

        $(document).on('change', '#tahun_ajaran, #kelas', function() {
            newJenjangId = $('#kelas').find('option:selected').data('jenjang');

            $.get("{{ route('laporan.jenis-pembayaran.data') }}", {
                tahun_ajaran: $('#tahun_ajaran').val(),
                kelas: $('#kelas').val(),
            }, function(data) {
                if(jenjangId != newJenjangId) {
                    jenjangId = newJenjangId;

                    $('#jenis_pembayaran').html('<option value="">-- Semua Jenis Pembayaran --</option>');

                    $.each(data, function(key, value) {
                        $('#jenis_pembayaran').append('<option value="' + value.id + '">' + value.nama + '</option>');
                    });
                }
            });
        })

        $('#tahun_ajaran').trigger('change');
        $('#kelas').trigger('change');
    });
</script>
@endsection
