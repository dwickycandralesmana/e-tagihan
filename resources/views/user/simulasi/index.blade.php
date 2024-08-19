@extends('layouts.app')

@section('title')
Simulasi
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Hasil
                </div>
                <h2 class="page-title">
                    Simulasi
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
                        <button data-bs-toggle="modal" data-bs-target="#modal-default" class="btn btn-primary mb-3 float-end">
                            <i class="fa fa-plus"></i> Mulai Simulasi
                        </button>
                    </div>
                    <div class="col-12">
                        <div class="table table-responsive">
                            <table id="table" class="table table-striped" style="width: 100%">
                                <thead>
                                    <th>No</th>
                                    <th>Nomor Ujian</th>
                                    <th>Simulasi Ujian</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
                                    <th>Rincian Nilai</th>
                                    <th>Nilai Total</th>
                                    <th>Status</th>
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

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Simulasi Ujian</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="skema" class="form-label fw-bold">Daftar Simulasi Ujian</label>
                    <select name="kompetensi" id="kompetensi" class="form-control select2">
                        @foreach ($simulasi as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn-validasi-kompetensi">Mulai Simulasi</button>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liSimulasi').addClass('active');
        $('.select2').select2({
            'dropdownParent': $('#modal-default')
        });

        let table = $("#table").DataTable({
            processing: false,
            serverSide: false,
            columnDefs: [{
                defaultContent: "-",
                targets       : "_all"
            }],
            ajax: {
                url: "{{ route('simulasi.data') }}",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: true, searchable: false},
                {data: 'nomor_ujian', name: 'nomor_ujian'},
                {data: 'ujian.nama', name: 'ujian.nama'},
                {data: 'waktu_mulai', name: 'waktu_mulai'},
                {data: 'waktu_selesai', name: 'waktu_selesai'},
                {data: 'rincian_nilai', name: 'rincian_nilai', orderable: false, searchable: false, width: '250px'},
                {data: 'total_nilai', name: 'total_nilai'},
                {data: 'status', name: 'status'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '10%'},
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
                    let url = '{{ route('simulasi.destroy', ':id') }}';
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

        var validasiKompetensi = async function(kompetensiId) {
            const myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");

            const raw = JSON.stringify({
                ujian_id: kompetensiId,
                _token       : '{{ csrf_token() }}',
            });

            const requestOptions = {
                method: "POST",
                headers: myHeaders,
                body: raw,
                redirect: "follow",
            };

            try{
                addSpinner($('.btn-validasi-kompetensi'));
                let response = await fetch('{{ route("simulasi.validasi") }}', requestOptions);
                let result = await response.json();

                if(result.success){
                    swalSuccess(result.message);
                    setTimeout(() => {
                        if(result.redirect){
                            window.location.href = result.url;
                        }
                    }, 1000);
                }else{
                    removeSpinner($('.btn-validasi-kompetensi'), "Mulai Kompetensi");
                    swalError(result.message);
                }
            }catch(err){
                removeSpinner($('.btn-validasi-kompetensi'), "Mulai Kompetensi");
            }
        }

        $(document).on('click', '.btn-validasi-kompetensi', function(){
            let kompetensiId = $('#kompetensi').val();

            validasiKompetensi(kompetensiId);
        });
    });
</script>
@endsection
