@extends('layouts.app')

@section('title')
    Ujian
@endsection

@section('styles')
    <style>
        .tab {
            display: none;
        }

        /* .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active {
            opacity: 1;
        }

        .step.finish {
            background-color: #04AA6D;
        } */
    </style>
@endsection

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Ujian
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div id="container-ujian">
        @if($ujian->status == "MENUNGGU_PERSETUJUAN" || $ujian->status == "DISETUJUI")
            <x-ujian.menunggu-persetujuan :ujian="$ujian"></x-ujian.menunggu-persetujuan>
        @elseif($ujian->status == "SEDANG_MENGERJAKAN")
            <x-ujian.dikerjakan :ujian="$ujian"></x-ujian.dikerjakan>
        @elseif($ujian->status == "TIDAK_LULUS")
            <x-ujian.tidak-lulus :ujian="$ujian"></x-ujian.tidak-lulus>
        @elseif($ujian->status == "LULUS")
            <x-ujian.lulus :ujian="$ujian"></x-ujian.lulus>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('#liKompetensi').addClass('active');
    var currentTab = 0;
    var totalSoal  = "{{ $ujian->soal->count() }}";

    @if(isset($order))
        @if($order->status == "PAID")
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Pembayaran berhasil dilakukan.'
            });
        @else
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Pembayaran gagal dilakukan.'
            });
        @endif
    @endif

    function showTab(n) {
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";

        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "";
        }

        if (n == (x.length - 1)) {
            $('#nextBtn').hide();
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            $('#nextBtn').show();
            document.getElementById("nextBtn").innerHTML = "<i class='fas fa-chevron-right'></i>";
        }

        fixStepIndicator(n)
    }

    // function nextPrev(n) {
    //     var x = document.getElementsByClassName("tab");

    //     // if (n == 1 && !validateForm()) return false;

    //     x[currentTab].style.display = "none";
    //     currentTab = currentTab + n;

    //     if (currentTab >= x.length) {
    //         document.getElementById("regForm").submit();
    //         return false;
    //     }

    //     showTab(currentTab);
    // }

    function nextPrev(n, override = false) {
        // let checkBaseTab = $('#base-tab').is(':visible');
        // if(checkBaseTab){
        //     generatePeopleForm();
        // }

        var x = document.getElementsByClassName("tab");
        let check = 0;
        if(override){
            check = 1;
        }

        // if (n > check && !validateForm(override, n)) return false;

        let tempTab = currentTab;

        if(override){
            currentTab = n;
        }else{
            currentTab = currentTab + n;
        }

        if (currentTab >= x.length) {
            // document.getElementById("base-form").submit();
            // return false;
        }

        if (typeof x[tempTab] != 'undefined') {

            x[tempTab].style.display = "none";
        } else {
            x[currentTab].style.display = "none";
        }

        showTab(currentTab);
    }

    function validateForm() {
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");

        for (i = 0; i < y.length; i++) {
            if (y[i].value == "") {
                y[i].className += " invalid";
                valid = false;
            }
        }

        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }

        return valid;
    }

    function fixStepIndicator(n) {
        let nomorSoal = n + 1;
        $('#nomorSoal').html('Soal ' + nomorSoal + ' / ' + totalSoal);
    }

    var requestFullScreen = function (element) {
        var elem = document.documentElement;
        if (!document.fullscreenElement) {
            elem.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
            });
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    };

    $(function(){
        if('{{ $ujian->status }}' == "SEDANG_MENGERJAKAN"){
            // if(window.outerHeight != screen.height){
            //     Swal.fire({
            //         title: 'Perhatian!',
            //         text: 'Sangat disarankan untuk menggunakan layar penuh agar tidak terjadi kesalahan saat mengerjakan ujian.',
            //         icon: 'warning',
            //         allowEscapeKey: false,
            //         confirmButtonText: 'OK'
            //     }).then((result) => {
            //         requestFullScreen();
            //     });
            // }
        }

        $(document).on('click', '#btnSelesai', function(){
            let pilihan = $('.pilihan-jawaban').is(':checked');

            if(!pilihan){
                swalError('Anda belum menjawab soal apapun!');

                return false;
            }

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
                    $('#form-ujian').submit();
                }
            });
        })

        $(document).on('click', '.btn-ragu', function(){
            console.log(123);
            let soal_id = $(this).data('soal-id');

            $.ajax({
                url: '{{ route('simulasi.ujian.ragu') }}',
                type: 'POST',
                data: {
                    soal_id : soal_id,
                    ujian_id: '{{ $ujian->id }}',
                    _token  : '{{ csrf_token() }}'
                },
                success: function(data){

                },
                error: function(data){
                    // swalError('Terjadi kesalahan! Mohon cek koneksi internet anda dan coba lagi.');
                }
            });
        })

        $(document).on('change', '.pilihan-jawaban', function(){
            let soal_id = $(this).data('soal-id');
            let soalId = $(this).data('soal');

            $('#btnSoal' + soalId).removeClass('btn-success btn-default btn-warning');
            $('#btnSoal' + soalId).addClass('btn-success');

            let jawaban = $(this).val();

            $.ajax({
                url: '{{ route('simulasi.ujian.jawab') }}',
                type: 'POST',
                data: {
                    _token  : '{{ csrf_token() }}',
                    ujian_id: '{{ $ujian->id }}',
                    soal_id : soal_id,
                    jawaban : jawaban
                },
                success: function(data){
                    if(data.success){
                        $('#soal-belum-dijawab').html(data.soal_belum_dijawab);
                        $('#soal-dijawab').html(data.soal_dijawab);
                    }else{
                        swalError(data.message);

                        if(data.submit_form){
                            $('#form-ujian').submit();
                        }
                    }

                },
                error: function(){
                    // swalError('Terjadi kesalahan! Mohon cek koneksi internet anda dan coba lagi.');
                }
            });
        });

        $(document).on('click', '.btn-ragu', function(){
            let soalId = $(this).data('soal');

            $('.pilihan-jawaban[data-soal="' + soalId + '"]').prop('checked', false);

            $('#btnSoal' + soalId).removeClass('btn-success btn-default btn-warning');
            $('#btnSoal' + soalId).addClass('btn-warning');

            nextPrev(1);
        })

    })
</script>
@endsection
