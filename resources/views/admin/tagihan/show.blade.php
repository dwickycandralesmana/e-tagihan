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
        <form action="{{ route('tagihan.update', $tagihan->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">NIS</label>
                                <input type="text" class="form-control" value="{{ $tagihan->siswa->nis }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" value="{{ $tagihan->siswa->nama }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Tahun Ajaran</label>
                                <input type="text" class="form-control" value="{{ $tagihan->tahun_ajaran }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" class="form-control" value="{{ $tagihan->kelas }}" name="kelas">
                            </div>
                        </div>
                        <div class="col-12 table-responsive">
                            <h3>
                                RINCIAN TAGIHAN KEUANGAN SISWA
                            </h3>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th style="width: 5%">No</th>
                                    <th colspan="2">Rincian</th>
                                    <th>Jumlah Tagihan</th>
                                    <th>Jumlah Pembayaran <br> <small class="text-muted">(termasuk potongan)</small></th>
                                    <th>Sisa Tagihan</th>
                                </thead>
                                <tbody>
                                    @php
                                        $totalBayar   = 0;
                                        $totalTagihan = 0;
                                    @endphp

                                    @forelse ($tagihan->tagihans as $key => $item)
                                        <input type="hidden" name="tagihan_id[]" value="{{ $item->id }}">
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td colspan="2">
                                                {{ $item->tipe_tagihan->nama }}

                                                @if(in_array($item->tipe_tagihan->key, ['tunggakan_kelas_x', 'tunggakan_kelas_xi']))
                                                    <div class="form-group mt-3">
                                                        <input type="text" class="form-control" name="deskripsi[{{ $item->id }}]" value="{{ $item->deskripsi }}" placeholder="Deskripsi Tunggakan">
                                                    </div>
                                                @endif

                                                @if(in_array($item->tipe_tagihan->key, ['angsuran_ujian', 'spp']))
                                                    <br>
                                                    <small class="text-muted">dihitung selama 12 bulan</small>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text" class="form-control number" name="total[{{ $item->id }}]" value="{{ $item->total }}" required>
                                            </td>
                                            @php
                                                $tempTotal = $item->pembayaran_details->sum('jumlah');
                                                $totalBayar += $tempTotal;
                                                $totalTagihan += $item->total;
                                            @endphp

                                            <td>
                                                {{ formatRp($tempTotal) }}
                                            </td>
                                            <td>
                                                {{ formatRp($item->total - $tempTotal) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Data tidak ditemukan</td>
                                        </tr>
                                    @endforelse

                                    <tr>
                                        <td colspan="5" class="text-end">
                                            <h3>Total Sisa Tagihan</h3>
                                        </td>
                                        <td>
                                            <h3>{{ formatRp($totalTagihan - $totalBayar) }}</h3>
                                        </td>
                                    </tr>

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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
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
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
                {data: 'nis', name: 'nis'},
                {data: 'nama', name: 'nama'},
                {data: 'jenjang.nama', name: 'jenjang.nama'},
                {data: 'kelas', name: 'kelas'},
                {data: 'action', name: 'action', orderable: false, searchable: false, width: '30%'},
            ],
        });

        $('input[type="text"].number').inputmask('currency', {
            'prefix': 'Rp ',
            'autoUnmask': true,
            'radixPoint': ",",
            'groupSeparator': ".",
            'digits': 0,
            'rightAlign': false,
            'removeMaskOnSubmit': true,
            'clearMaskOnLostFocus': true,
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
