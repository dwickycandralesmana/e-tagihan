@extends('frontend.app')
@section('title', $form->name . ' - Aplikasi Anggota PERADI')
@section('content')
    <div class="container-xl mt-3 px-3">
        <form action="{{ route('anggota.formulir.store', $form->slug) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ $brandLogo }}" alt="PERADI" class="img-fluid" style="width: 250px;">
                    </a>
                </div>
                <div class="col-12 mt-5 fw-bold">
                    <h3 class="text-center">{{ $form->name }}</h3>
                </div>
            </div>
            @include('components.formulir.form_' . $form->id)
            <div class="row">
                <div class="col-12">
                    {!! $form->description !!}
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <strong>Perhatian!</strong> Pastikan data yang anda masukkan sudah benar sebelum melanjutkan.
                                Anda akan dikenakan biaya sebesar <strong>Rp. {{ number_format($form->price, 0, ',', '.') }}</strong> untuk proses ini.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <a href="{{ route('home') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
                <div class="col-12 mt-3">
                    {!! $form->disclaimer !!}
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('.select2').select2();

    function getIp(callback) {
        fetch('https://ipinfo.io/json', { headers: { 'Accept': 'application/json' }})
        .then((resp) => resp.json())
        .catch(() => {
            return {
            country: 'id',
            };
        })
        .then((resp) => callback(resp.country));
    }

    const phoneInputField = document.querySelector("#telPhone");
    const phoneInput = window.intlTelInput(phoneInputField, {
        initialCountry: "auto",
        geoIpLookup: getIp,
        preferredCountries: ["id", "us", "au"],
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js",
    });

    const phoneInfo = document.querySelector(".phone-info");
    const phoneError = document.querySelector(".phone-error");

    function phoneProcess(event) {
        event.preventDefault();
        const phoneNumber = phoneInput.getNumber();

        phoneInfo.style.display = "none";
        phoneError.style.display = "none";

        if (phoneInput.isValidNumber()) {
            phoneInfo.style.display = "";
            // info.innerHTML = `Phone number : <strong>${phoneNumber}</strong>`;
            $('.findPhone').val(phoneNumber)
        } else {
            phoneError.style.display = "";
            phoneError.innerHTML = `Invalid phone number.`;
        }
    }

    //check telfirmphone is exist
    if ($('#telFirmPhone').length) {
        const firmPhoneInputField = document.querySelector("#telFirmPhone");
        const firmPhoneInput = window.intlTelInput(firmPhoneInputField, {
            initialCountry: "auto",
            geoIpLookup: getIp,
            preferredCountries: ["id", "us", "au"],
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js",
        });

        const firmPhoneInfo = document.querySelector(".phone-info");
        const firmPhoneError = document.querySelector(".phone-error");

        function firmPhoneProcess(event) {
            event.preventDefault();
            const phoneNumber = phoneInput.getNumber();

            firmPhoneInfo.style.display = "none";
            firmPhoneError.style.display = "none";

            if (phoneInput.isValidNumber()) {
                firmPhoneInfo.style.display = "";
                // info.innerHTML = `Phone number : <strong>${phoneNumber}</strong>`;
                $('.findFirmPhone').val(phoneNumber)
            } else {
                firmPhoneError.style.display = "";
                firmPhoneError.innerHTML = `Invalid phone number.`;
            }
        }
    }

    $(function() {
        $(document).on('keyup','#telPhone',function (event) {
            phoneProcess(event);
        })

        if ($('#telFirmPhone').length) {
            $(document).on('keyup','#telFirmPhone',function (event) {
                firmPhoneProcess(event);
            })
        }
    });
</script>
@endsection
