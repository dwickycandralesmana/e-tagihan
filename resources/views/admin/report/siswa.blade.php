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
                        <div class="table table-responsive">
                            <table id="table" class="table table-striped" style="width: 100%">
                                <thead>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
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
        $('#liLaporanSiswa').addClass('active');
        $('#liLaporan').addClass('active');
        $('#liLaporan .dropdown-menu').addClass('show');
        $('.select2').select2();

        let table = $("#table").DataTable({
            processing: false,
            serverSide: false,
            columnDefs: [{
                defaultContent: "-",
                targets       : "_all"
            }],
            ajax: {
                url: "{{ route('laporan.siswa.data') }}",
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

        $(document).on('click','#btnFilter', function(){
            table.ajax.reload();
        })

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
                    let url = '{{ route('siswa.destroy', ':id') }}';
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
