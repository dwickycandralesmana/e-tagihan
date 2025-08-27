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
            <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data" id="formPembayaran">
                @csrf
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
                                <input type="date" class="form-control @error('tanggal_pembayaran') is-invalid @enderror" id="tanggal_pembayaran" name="tanggal_pembayaran" value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}">

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
                                <select name="metode_pembayaran" id="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror select2" required>
                                    <option value="">-- Pilih Metode Pembayaran --</option>
                                    <option value="Cash" {{ old('metode_pembayaran') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="Transfer" {{ old('metode_pembayaran') == 'Transfer' ? 'selected' : '' }}>Transfer</option>
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

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-start fw-bold">Total</td>
                                            <td class="fw-bold" id="totalBayar">0</td>
                                            <td class="fw-bold" id="totalPotongan">0</td>
                                            <td class="fw-bold" id="totalJumlah">0</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <button type="button" class="btn btn-primary mt-3" id="addRow"><i class="fas fa-plus"></i> Tambah Baris</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success btn-submit" type="button"><i class="fas fa-check"></i> Simpan</button>
                    <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script type="text/javascript">
    var formatRupiah = function(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        }).format(number);
    };

    var parseNumber = function(number) {
        return parseFloat(number.replace(/[^0-9]/g, ''));
    };

    var generateTotal = function() {
        let totalBayar     = 0;
        let totalPotongan  = 0;
        let totalJumlah    = 0;

        $('.tr-item').each(function() {
            let bayar     = parseNumber($(this).find('.bayar').val());
            let potongan  = parseNumber($(this).find('.potongan').val());
            let jumlah    = parseNumber($(this).find('.jumlah').val());

            totalBayar     += bayar;
            totalPotongan  += potongan;
            totalJumlah    += jumlah;
        });

        $('#totalBayar').text(formatRupiah(totalBayar));
        $('#totalPotongan').text(formatRupiah(totalPotongan));
        $('#totalJumlah').text(formatRupiah(totalJumlah));
    };

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

            generateTotal();
       });
    });

    let paidMonth         = @json($paidMonth);
    let paidMonthAngsuran = @json($paidMonthAngsuran);
    let thisYear          = {{ $historyKelas->tahun_ajaran }};
    let nextYear          = {{ $historyKelas->tahun_ajaran + 1 }};

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
                            <option value="{{ $value->id }}" data-type="{{ $value->tipe_tagihan->key }}" data-deskripsi="{{ $value->deskripsi }}">
                                {{ $value->tipe_tagihan->nama }}
                            </option>
                        @endforeach
                    </select>

                    @foreach($details as $key => $value)
                        <div class="form-group kekurangan-container" id="kekurangan-{{ $value->id }}" style="display: none;">
                            <label for="kekurangan" class="form-label mt-3">Kekurangan {{ $value->tipe_tagihan->nama }}</label>
                            <input type="text" name="details[${random}][kekurangan]" class="form-control kekurangan number" value="{{ $value->total - $value->pembayaran_details->sum('jumlah') }}">
                        </div>

                        <input type="hidden" name="placeholder[${random}][total]" id="total-{{ $value->id }}" value="{{ $value->total }}">
                        <input type="hidden" name="placeholder[${random}][potongan]" id="potongan-{{ $value->id }}" value="{{ $value->potongan }}">
                    @endforeach

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

                    <div class="bulan-angsuran" style="display: none;">
                        <label for="bulan" class="form-label mt-3">Bulan</label>
                        <select name="details[${random}][bulan_angsuran]" class="form-select select2">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach(range(7, 12) as $month)
                                @if (!in_array($month, $paidMonthAngsuran))
                                    <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }} ${thisYear}</option>
                                @endif
                            @endforeach
                            @foreach(range(1, 6) as $month)
                                @if (!in_array($month, $paidMonthAngsuran))
                                    <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->locale('id')->translatedFormat('F') }} ${nextYear}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="deskripsi-tunggakan" style="display: none;">
                        <label for="deskripsi" class="form-label mt-3">Deskripsi Tunggakan</label>
                        <input type="text" name="details[${random}][deskripsi]" class="form-control deskripsi">
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

    $(document).on('change', '.bulan', function() {
        let bulan = $(this).find('option:selected').val();
        let container = $(this).closest('.tr-item');
        let bayar     = container.find('.bayar');
        let tagihanId = container.find('.tipe-tagihan').val();
        let type      = container.find('.tipe-tagihan option:selected').data('type');

        let jenjang   = '{{ $historyKelas->jenjang_id }}';
        let potongan  = container.find('.potongan');
        let jumlah    = container.find('.jumlah');

        if(type == 'spp' || type == 'angsuran_ujian') {
            if(jenjang == 1) {
                if(bulan == 7){
                    bayar.val(0);
                    potongan.val(0);
                    jumlah.val(0);
                }else{
                    sumPotongan = parseFloat($(`#potongan-${tagihanId}`).val()) / 11;
                    sumBayar = parseFloat($(`#total-${tagihanId}`).val()) / 11;

                    sumBayar = sumBayar - sumPotongan;

                    bayar.val(sumBayar);
                    potongan.val(sumPotongan);
                    jumlah.val(sumBayar + sumPotongan);
                }
            }else{
                sumPotongan = parseFloat($(`#potongan-${tagihanId}`).val()) / 12;
                sumBayar = parseFloat($(`#total-${tagihanId}`).val()) / 12;

                sumBayar = sumBayar - sumPotongan;

                bayar.val(sumBayar);
                potongan.val(sumPotongan);
                jumlah.val(sumBayar + sumPotongan);
            }
        }

        generateTotal();
    });

    $(document).on('change', '.tipe-tagihan', function() {
        let type               = $(this).find('option:selected').data('type');
        let container          = $(this).closest('.tr-item');
        let bulan              = container.find('.bulan');
        let bulanAngsuran      = container.find('.bulan-angsuran');
        let deskripsiTunggakan = container.find('.deskripsi-tunggakan');
        let deskripsi          = $(this).find('option:selected').data('deskripsi');

        bulan.find('select').val('').trigger('change');
        bulanAngsuran.find('select').val('').trigger('change');
        deskripsiTunggakan.hide();
        deskripsiTunggakan.find('.deskripsi').val('');

        container.find('.bayar').val('0');
        container.find('.potongan').val('0');
        container.find('.jumlah').val('0');

        let bayar    = 0;
        let potongan = 0;

        if (type == 'spp') {
            bulan.show();
        } else {
            bulan.hide();
        }

        if (type == 'angsuran_ujian') {
            bulanAngsuran.show();
        } else {
            bulanAngsuran.hide();
        }

        if(['tunggakan_kelas_x', 'tunggakan_kelas_xi'].includes(type)) {
            deskripsiTunggakan.show();
            deskripsiTunggakan.find('.deskripsi').val(deskripsi);
        }

        let tagihanId = $(this).val();
        container.find('.kekurangan-container').hide();
        let kekurangan = container.find(`#kekurangan-${tagihanId}`).show();

        let jenjang = '{{ $historyKelas->jenjang_id }}';
        if(type == 'spp' || type == 'angsuran_ujian') {
            if(jenjang == 1) {
                potongan = $(`#potongan-${tagihanId}`).val() / 11;
                bayar = $(`#total-${tagihanId}`).val() / 11;

                bayar = bayar - potongan;
            }else {
                potongan = $(`#potongan-${tagihanId}`).val() / 12;
                bayar = $(`#total-${tagihanId}`).val() / 12;

                bayar = bayar - potongan;
            }
        }

        if(bayar > 0) {
            container.find('.bayar').val(bayar);
        }

        if(potongan > 0) {
            container.find('.potongan').val(potongan);
        }

        let jumlah = (parseFloat(container.find('.bayar').val()) + parseFloat(container.find('.potongan').val()));

        generateTotal();
    });

    $('.btn-submit').on('click', function() {
        $(this).prop('disabled', true);
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

           $(this).prop('disabled', false);
           return false;
       }

       //get visible bulan
       let bulan = [];
       $('.bulan:visible select').each(function() {
           bulan.push($(this).val());
       });


       //check duplicate bulan
       let uniqueBulan = bulan.filter((value, index, self) => {
           return self.indexOf(value) === index;
       });

       if(uniqueBulan.length < bulan.length) {
           Swal.fire({
               icon: 'error',
               title: 'Gagal',
               text: 'Bulan yang dipilih tidak boleh sama!',
           });

           $(this).prop('disabled', false);
           return false;
       }

       let isValidPayment = true
       $('.jumlah').each(function() {
           let container = $(this).closest('.tr-item');
           let kekuranganContainer = container.find('.kekurangan-container:visible');
           let jumlah = parseFloat($(this).val());
           let kekurangan = parseFloat(kekuranganContainer.find('.kekurangan').val());

           if(jumlah > kekurangan) {
               isValidPayment = false;
           }
       });

       if(!isValidPayment) {
           Swal.fire({
               icon: 'error',
               title: 'Gagal',
               text: 'Jumlah pembayaran melebihi kekurangan tagihan!',
           });

           $(this).prop('disabled', false);
           return false;
       }

       if (!isValid) {
           Swal.fire({
               icon: 'error',
               title: 'Gagal',
               text: 'Harap isi jumlah tagihan!',
           });

           $(this).prop('disabled', false);
           return false;
       }

       if($('#metode_pembayaran').val() == '') {
           Swal.fire({
               icon: 'error',
               title: 'Gagal',
               text: 'Harap pilih metode pembayaran!',
           });

           $(this).prop('disabled', false);
           return false;
       }

       $.ajax({
           url: "{{ route('pembayaran.store') }}",
           method: "POST",
           data: $('#formPembayaran').serialize(),
           success: function(response) {
               console.log(response);

               Swal.fire({
                   icon: 'success',
                   title: 'Berhasil',
                   text: 'Pembayaran berhasil! Ingin unduh bukti pembayaran?',
                   showCancelButton: true,
                   confirmButtonText: 'Ya',
                   cancelButtonText: 'Tidak',
               }).then((result) => {
                   if (result.isConfirmed) {
                       window.location.href = response.url;
                   }else{
                       window.location.href = "{{ route('pembayaran.index') }}";
                   }
               });
           },
           error: function(xhr) {
               Swal.fire({
                   icon: 'error',
                   title: 'Gagal',
                   text: xhr.responseJSON.message,
               });

               $(this).prop('disabled', false);
           }
       });
    });
</script>
@endsection
