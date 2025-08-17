@extends('layouts.app')

@section('title')
Jenis Pembayaran
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
                    Jenis Pembayaran
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
                    <div class="col-12">
                        <a href="{{ route('jenis-pembayaran.create') }}" class="btn btn-primary float-end">
                            <i class="fa fa-plus"></i> Tambah Jenis Pembayaran
                        </a>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="tahun_ajaran" class="fw-bold">Tahun Ajaran</label>
                            <select name="tahun_ajaran" id="tahun_ajaran" class="form-control select2">
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                @for ($i = date('Y'); $i >= 2019; $i--)
                                    <option value="{{ $i }}" @if ($i == getDefaultTA()) selected @endif>{{ $i }}</option>
                                @endfor
                            </select>

                            <button type="button" class="btn btn-primary mt-3 float-end" id="btnFilter">Filter</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="table table-responsive">
                            <table id="table" class="table table-striped" style="width: 100%">
                                <thead>
                                    <th>No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Jenjang</th>
                                    <th>Nama</th>
                                    <th>Total</th>
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
        $('#liJenisPembayaran').addClass('active');

        let table = $("#table").DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [{
                defaultContent: "-",
                targets       : "_all"
            }],
            ajax: {
                url: "{{ route('jenis-pembayaran.data') }}",
                data: function (d) {
                    d.tahun_ajaran = $('#tahun_ajaran').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-start'},
                {data: 'tahun_ajaran', name: 'tahun_ajaran', className: 'text-start'},
                {data: 'jenjang.nama', name: 'jenjang.nama', className: 'text-start'},
                {data: 'nama', name: 'nama'},
                {data: 'total', name: 'total'},
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
                    let url = '{{ route('jenis-pembayaran.destroy', ':id') }}';
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
