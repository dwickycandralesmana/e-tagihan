@extends('layouts.app')

@section('title')
Tagihan
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
                    Tagihan
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
                            <label for="kelas" class="fw-bold">Kelas</label>
                            <select name="kelas" id="kelas" class="form-control select2">
                                <option value="">-- Semua Kelas --</option>
                                @foreach ($listKelas as $item)
                                    <option value="{{ $item }}">{{ $item }}</option>
                                @endforeach
                            </select>

                            <button type="button" class="btn btn-primary mt-3 float-end" id="btnFilter">Filter</button>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modalImport">
                            <i class="fa fa-upload"></i> Import
                        </button>
                        <div class="table table-responsive">
                            <table id="table" class="table table-striped" style="width: 100%">
                                <thead>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Tahun Pelajaran</th>
                                    <th>Jenjang</th>
                                    <th>Kelas</th>
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

<div class="modal modal-blur fade" id="modalImport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Tagihan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tagihan.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">Jenjang</label>
                        <select class="form-select" name="jenjang_id" required>
                            <option value="">Pilih Jenjang</option>
                            @foreach($jenjang as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">File</label>
                        <input type="file" class="form-control" name="file" required accept=".xlsx">
                        <div class="form-text">File harus berupa .xlsx</div>
                    </div>

                    <a href="{{ asset('imports/template-kelas.xlsx') }}" class="btn btn-success"><i class="fas fa-file-excel"></i> Download Template</a>

                    {{-- <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Pastikan file yang diupload sesuai dengan format yang telah ditentukan. Silahkan download formatnya terlebih dahulu dari <b>"Menu Jenjang".</b>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liTagihan').addClass('active');
        $('.select2').select2();

        let table = $("#table").DataTable({
            processing: true,
            serverSide: true,
            columnDefs: [{
                defaultContent: "-",
                targets       : "_all"
            }],
            ajax: {
                url: "{{ route('tagihan.data') }}",
                data: function (d) {
                    d.tahun_ajaran = $('#tahun_ajaran').val();
                    d.kelas = $('#kelas').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
                {data: 'siswa.nis', name: 'siswa.nis', className: 'text-start'},
                {data: 'siswa.nama', name: 'siswa.nama'},
                {data: 'tahun_ajaran', name: 'tahun_ajaran'},
                {data: 'jenjang.nama', name: 'jenjang.nama'},
                {data: 'kelas', name: 'kelas'},
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
                text: "Anda tidak dapat mengulangi tindakan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, saya yakin!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.value) {
                    let url = '{{ route('tagihan.destroy', ':id') }}';
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
