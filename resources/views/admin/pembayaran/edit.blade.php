@extends('layouts.app')

@section('title')
Tambah Pembayaran
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Tambah
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
            <div class="card-header fw-bold">
                Mohon lengkapi seluruh kolom yang dibutuhkan.
            </div>
            <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data" id="formPembayaran">
                @csrf
                @method('PUT')
                <input type="hidden" name="history_kelas_id" value="{{ $historyKelas->id }}">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">NIS</label>
                                <input type="text" class="form-control" value="{{ $historyKelas->siswa->nis }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" class="form-control" value="{{ $historyKelas->siswa->nama }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Tahun Pelajaran</label>
                                <input type="text" class="form-control" value="{{ $historyKelas->tahun_ajaran }}/{{ $historyKelas->tahun_ajaran + 1 }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" class="form-control" value="{{ $historyKelas->kelas }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="tanggal_pembayaran" class="form-label fw-bold">Tanggal Bayar</label>
                                <input type="date" class="form-control @error('tanggal_pembayaran') is-invalid @enderror" id="tanggal_pembayaran" name="tanggal_pembayaran" value="{{ $pembayaran->tanggal_pembayaran }}">

                                @error('tanggal_pembayaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="metode_pembayaran" class="form-label fw-bold">Metode Pembayaran</label>
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror select2">
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="Cash" {{ $pembayaran->metode_pembayaran == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Transfer" {{ $pembayaran->metode_pembayaran == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>

                                @error('metode_pembayaran')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="details" class="form-label fw-bold">DETAIL PEMBAYARAN</label>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tagihan</th>
                                            <th>Bayar</th>
                                            <th>Potongan</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pembayaran->details as $key => $value)
                                            <tr class="tr-item">
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    <input type="hidden" name="details[{{ $value->id }}][id]" value="1">
                                                    <select name="details[{{ $value->id }}][tagihan_new_id]" class="form-select select2 tipe-tagihan">
                                                        <option value="">-- Pilih Tagihan --</option>
                                                        @foreach ($details as $k2 => $v2)
                                                            <option value="{{ $v2->id }}" data-type="{{ $v2->tipe_tagihan->key }}" @selected($v2->id == $value->tagihan_new_id)>{{ $v2->tipe_tagihan->nama }}</option>
                                                        @endforeach
                                                    </select>

                                                    @php
                                                        $thisYear = $historyKelas->tahun_ajaran;
                                                        $nextYear = $thisYear + 1;
                                                    @endphp

                                                    <div class="bulan" @if($value->bulan == null) style="display: none;" @endif>
                                                        <label for="bulan" class="form-label mt-3">Bulan</label>
                                                        <select name="details[{{ $value->id }}][bulan]" class="form-select select2">
                                                            <option value="">-- Pilih Bulan --</option>
                                                            <option value="7" @selected($value->bulan == 7)>Juli {{ $thisYear }}</option>
                                                            <option value="8" @selected($value->bulan == 8)>Agustus {{ $thisYear }}</option>
                                                            <option value="9" @selected($value->bulan == 9)>September {{ $thisYear }}</option>
                                                            <option value="10" @selected($value->bulan == 10)>Oktober {{ $thisYear }}</option>
                                                            <option value="11" @selected($value->bulan == 11)>November {{ $thisYear }}</option>
                                                            <option value="12" @selected($value->bulan == 12)>Desember {{ $thisYear }}</option>
                                                            <option value="1" @selected($value->bulan == 1)>Januari {{ $nextYear }}</option>
                                                            <option value="2" @selected($value->bulan == 2)>Februari {{ $nextYear }}</option>
                                                            <option value="3" @selected($value->bulan == 3)>Maret {{ $nextYear }}</option>
                                                            <option value="4" @selected($value->bulan == 4)>April {{ $nextYear }}</option>
                                                            <option value="5" @selected($value->bulan == 5)>Mei {{ $nextYear }}</option>
                                                            <option value="6" @selected($value->bulan == 6)>Juni {{ $nextYear }}</option>
                                                    </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $value->id }}][bayar]" class="form-control bayar number" value="{{ $value->bayar }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $value->id }}][potongan]" class="form-control potongan number" value="{{ $value->potongan }}">
                                                </td>
                                                <td>
                                                    <input type="text" name="details[{{ $value->id }}][jumlah]" class="form-control jumlah number" value="{{ $value->jumlah }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-delete-row"><i class="fas fa-trash me-0"></i></button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Data tidak ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-primary mt-3" id="addRow"><i class="fas fa-plus"></i> Tambah Baris</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success btn-submit" type="submit"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#liPembayaran').addClass('active');
        $('.select2').select2();

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

       $(document).on('input', '.bayar, .potongan', function() {
            let container = $(this).closest('.tr-item');
            let bayar     = parseFloat(container.find('.bayar').val());
            let potongan  = parseFloat(container.find('.potongan').val());
            let jumlah    = container.find('.jumlah');

            jumlah.val(bayar + potongan);
       });
    });

    let paidMonth = @json($paidMonth);
    let thisYear = {{ $historyKelas->tahun_ajaran }};
    let nextYear = {{ $historyKelas->tahun_ajaran + 1 }};

    $(document).on('click', '#addRow', function() {
        let index = $('.tr-item').length;
        let random = Math.floor(Math.random() * 1000000);
        let row = `
            <tr class="tr-item">
                <td class="text-center">
                    ${index + 1}
                </td>
                <td>
                    <input type="hidden" name="details[${random}][id]" value="1">
                    <select name="details[${random}][tagihan_new_id]" class="form-select select2 tipe-tagihan">
                        <option value="">-- Pilih Tagihan --</option>
                        @foreach ($details as $key => $value)
                            <option value="{{ $value->id }}" data-type="{{ $value->tipe_tagihan->key }}">{{ $value->tipe_tagihan->nama }}</option>
                        @endforeach
                    </select>

                    <div class="bulan" style="display: none;">
                        <label for="bulan" class="form-label mt-3">Bulan</label>
                        <select name="details[${random}][bulan]" class="form-select select2">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach(range(7, 12) as $month)
                                @if (!in_array($month, $paidMonth))
                                    <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }} ${thisYear}</option>
                                @endif
                            @endforeach
                            @foreach(range(1, 6) as $month)
                                @if (!in_array($month, $paidMonth))
                                    <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }} ${nextYear}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </td>
                <td>
                    <input type="text" name="details[${random}][bayar]" class="form-control bayar number" value="0">
                </td>
                <td>
                    <input type="text" name="details[${random}][potongan]" class="form-control potongan number" value="0">
                </td>
                <td>
                    <input type="text" name="details[${random}][jumlah]" class="form-control jumlah number" value="0" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-delete-row"><i class="fas fa-trash me-0"></i></button>
                </td>
            </tr>
        `

        $('.tr-item').each(function(index) {
            $(this).find('td:first').text(index + 1);
        });

        $('table tbody').append(row);
        $('.select2').select2();
        $('.number').inputmask('currency', {
            'prefix': 'Rp ',
            'autoUnmask': true,
            'radixPoint': ",",
            'groupSeparator': ".",
            'digits': 0,
            'rightAlign': false,
            'removeMaskOnSubmit': true,
            'clearMaskOnLostFocus': true,
        });
    });

    $(document).on('click', '.btn-delete-row', function() {
        $(this).closest('.tr-item').remove();

        $('table tbody tr').each(function(index){
            $(this).find('td:first').text(index + 1);
        });
    });

    $(document).on('change', '.tipe-tagihan', function() {
        let type      = $(this).find('option:selected').data('type');
        let container = $(this).closest('.tr-item');
        let bulan     = container.find('.bulan');
        bulan.find('select').val('').trigger('change');

        if (type == 'spp') {
            bulan.show();
        } else {
            bulan.hide();
        }
    });

    $('.btn-submit').on('click', function() {
       //check if bulan visible but not selected yet
       let isValid = true;
       $('.bulan').each(function() {
           if ($(this).is(':visible') && $(this).find('select').val() == '') {
               isValid = false;
               $(this).find('select').addClass('is-invalid');
           }
       });

       if (!isValid) {
           Swal.fire({
               icon: 'error',
               title: 'Gagal',
               text: 'Harap pilih bulan untuk tagihan yang terpilih!',
           });
           return false;
       }

       $('#formPembayaran').submit();
    });
</script>
@endsection
