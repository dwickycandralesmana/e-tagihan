@extends('layouts.app')

@section('title')
Pembayaran
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Daftar
                </div>
                <h2 class="page-title">
                    Pembayaran
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
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
                            <label for="tanggal_pembayaran" class="fw-bold">Tanggal Pembayaran</label>
                            <input type="text" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control daterangepicer" value="{{ \Carbon\Carbon::now()->subDays(7)->format('d/m/Y') . ' - ' . \Carbon\Carbon::now()->format('d/m/Y') }}">

                            <button type="button" class="btn btn-primary mt-3 float-end" id="btnFilter">Filter</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table table-responsive">
                            <table id="table" class="table table-striped" style="width: 100%">
                                <thead>
                                    <th>No</th>
                                    <th>Tahun Pelajaran</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Jenjang</th>
                                    <th>Nama</th>
                                    <th>Detail Pembayaran</th>
                                    <th>Total Potongan</th>
                                    <th>Total Bayar</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Aksi</th>
                                </thead>
                            </table>
                        </div>
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
        $('.select2').select2();
        $('#liPembayaran').addClass('active');
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

        let table = $("#table").DataTable({
            processing: false,
            serverSide: false,
            columnDefs: [{
                defaultContent: "-",
                targets       : "_all"
            }],
            ajax: {
                url: "{{ route('pembayaran.data') }}",
                data: function (d) {
                    d.tahun_ajaran = $('#tahun_ajaran').val();
                    d.tanggal_pembayaran = $('#tanggal_pembayaran').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-start'},
                {data: 'tahun_ajaran', name: 'tahun_ajaran', className: 'text-start'},
                {data: 'tanggal_pembayaran', name: 'tanggal_pembayaran', className: 'text-start'},
                {data: 'history_kelas.jenjang.nama', name: 'history_kelas.jenjang.nama', className: 'text-start'},
                {data: 'history_kelas.siswa.nama', name: 'history_kelas.siswa.nama'},
                {data: 'details', name: 'details', orderable: false, searchable: false},
                {data: 'total_potongan', name: 'total_potongan'},
                {data: 'total_bayar', name: 'total_bayar'},
                {data: 'created_by.name', name: 'created_by.name'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '30%'},
            ],
        });

        $('#btnFilter').on('click', function() {
            table.ajax.reload();
        });

        $(document).on('click','.btn-delete', function(){
            id = $(this).attr('data-id');

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Ini akan menghapus semua data siswa yang terkait!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya yakin!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    let url = '{{ route('pembayaran.destroy', ':id') }}';
                        url = url.replace(':id', id);

                    $.ajax({
                        url : url,
                        type : 'delete',
                        data : {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response){
                            if(response.status){
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message
                                });
                            }else{
                                Toast.fire({
                                    icon: 'error',
                                    title: response.message
                                });
                            }

                            table.ajax.reload();
                        },
                        error: function(e){
                            Toast.fire({
                                icon: 'error',
                                title: e.responseJSON.message
                            });
                        }
                    });
                }
            })
        })
    });
</script>
@endsection
