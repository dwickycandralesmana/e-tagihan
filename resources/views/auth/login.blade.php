@extends('auth.base')

@section('title', 'Login')

@section('content')
<div class="container container-tight py-4">
    <div class="text-center mb-4">
        <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark">
            <img src="{{ $brandLogo }}" height="200" alt="">
        </a>
    </div>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Login to your account</h2>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
                @endif
            <form action="{{ route('login') }}" method="POST" autocomplete="off" id="form">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" class="form-control" placeholder="your@email.com" autocomplete="off" required name="email" id="email">
                </div>
                <div class="mb-2">
                    <label class="form-label">
                        Password
                        <span class="form-label-description">
                            <a href="{{ route('password.request') }}">I forgot password</a>
                        </span>
                    </label>
                    <div class="input-group input-group-flat">
                        <input type="password" class="form-control" placeholder="Your password" autocomplete="off" required name="password" id="password">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary show-password" >
                                <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path
                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                    </svg>
                            </a>
                        </span>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="otp" class="form-label">OTP</label>
                    <input type="text" name="otp" id="otp" class="form-control" required>
                    <div id="otp-container">
                        <a href="#" id="otp-button">Klik disini untuk mendapatkan OTP</a>
                    </div>

                    @error('otp')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Sign in</button>
                </div>
            </form>
        </div>
    </div>
    {{-- <div class="text-center text-muted mt-3">
        Don't have account yet? <a href="{{ route('register') }}" tabindex="-1">Sign up</a>
    </div> --}}
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function () {
        $('.show-password').click(function () {
            var $this = $(this),
                $password = $this.closest('.input-group').find('#password');

            if ($password.attr('type') === 'password') {
                $password.attr('type', 'text');
                $this.find('svg').removeClass('icon-eye').addClass('icon-eye-off');
            } else {
                $password.attr('type', 'password');
                $this.find('svg').removeClass('icon-eye-off').addClass('icon-eye');
            }
        });

        var swalError = function (message) {
            Swal.fire({
                icon          : 'error',
                title         : 'Oops...',
                html          : message,
                reverseButtons: true,
            });
        }

        var swalSuccess = function (message) {
            Swal.fire({
                icon          : 'success',
                title         : 'Success',
                html          : message,
                reverseButtons: true,
            });
        }

        var addSpinner = function (element) {
            element.attr('disabled', true);
            element.html(`
                <div class='spinner-border spinner-border-sm' role='status'>
                    <span class='sr-only'>Loading...</span>
                </div>
            `);
        }

        var removeSpinner = function (element, text) {
            element.attr('disabled', false);
            element.html(text);
        }

        $('#otp-button').on('click', function(e) {
            e.preventDefault();
            const email = $('#email').val();
            if (email) {
                $('#otp-container').html('Kami sedang mengirim OTP...');

                $.ajax({
                    url: "{{ route('login.otp') }}",
                    type: 'POST',
                    data: {
                        email: email,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#otp-container').html('Kami sudah mengirim OTP ke email anda, jika ada kendala silahkan hubungi admin');

                            $("#form").append(`
                                <input type="hidden" name="otp_id" value="${response.otp_id}">
                            `);
                            swalSuccess(response.message);
                        } else {
                            $('#otp-container').html('<a href="#" id="otp-button">Klik disini untuk mendapatkan OTP</a>');

                            swalError(response.message);
                        }
                    },
                    error: function(response) {
                        $('#otp-container').html('<a href="#" id="otp-button">Klik disini untuk mendapatkan OTP</a>');

                        swalError('Terjadi kesalahan');
                    }
                });
            } else {
                swalError('Email tidak valid');
            }
        });
    });
</script>
@endsection
