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
                    Detail
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
                    <div class="col-md-4 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" class="form-control" value="{{ $tagihan->nis }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" value="{{ $tagihan->nama }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" class="form-control" value="{{ $tagihan->kelas }}" readonly>
                        </div>
                    </div>
                    <div class="col-12">
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
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liTagihan').addClass('active');
        let table = $("#table").DataTable({
            processing: false,
            serverSide: false,
            columnDefs: [{
                defaultContent: "-",
                targets       : "_all"
            }],
            ajax: {
                url: "{{ route('tagihan.data') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false, width: '5%'},
                {data: 'nis', name: 'nis'},
                {data: 'nama', name: 'nama'},
                {data: 'jenjang.nama', name: 'jenjang.nama'},
                {data: 'kelas', name: 'kelas'},
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
                    let url = '{{ route('jenjang.destroy', ':id') }}';
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
