@extends('layouts.app')

@section('title')
User
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
                    User
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
                        <a href="{{ route('user.create') }}" class="btn btn-primary float-end">
                            <i class="fa fa-plus"></i> Tambah User
                        </a>

                        {{-- <button type="button" id="btnImport" class="btn btn-success me-1 float-end" data-bs-toggle="modal" data-bs-target="#modalImport"><i class="fas fa-file-import"></i> Import</button> --}}
                    </div>
                    <div class="col-12">
                        <div class="table table-responsive">
                            <table id="table" class="table table-striped" style="width: 100%">
                                <thead>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
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

<div class="modal fade" id="modalImport">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.import') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Import Data Pengguna</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <a target="_blank" href="{{ asset('imports/soal.xlsx') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Download Template</a>
                    <div class="form-group mt-3">
                        <label for="" class="fw-bold">File Excel</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                        <small class="text-muted">File yang diupload harus bertipe .xlsx</small>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liUser').addClass('active');
        let table = $("#table").DataTable({
            processing: false,
            serverSide: false,
            columnDefs: [{
                defaultContent: "-",
                targets       : "_all"
            }],
            ajax: {
                url: "{{ route('user.data') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '30%'},
            ],
        });

        $(document).on('click','.btn-delete', function(){
            id = $(this).attr('data-id');

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak dapat mengulangi tindakan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya yakin!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    let url = '{{ route('user.destroy', ':id') }}';
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
