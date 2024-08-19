@extends('auth.base')

@section('title', 'Register')

@section('content')
<div class="container container-tight py-4">
    <div class="text-center mb-4">
        <a href="{{ route('home') }}" class="navbar-brand navbar-brand-autodark">
            <img src="{{ asset('assets/img/logo.webp') }}" height="80" alt="">
        </a>
    </div>
    <div class="card card-md">
        <div class="card-body">
            <h2 class="h2 text-center mb-4">Create new account</h2>

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
                @endif
            <form action="{{ route('register') }}" method="POST" autocomplete="off" >
                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" placeholder="John Doe" autocomplete="off" required name="name">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" class="form-control" placeholder="your@email.com" autocomplete="off" required name="email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-control" placeholder="08123456789" autocomplete="off" required name="nomor_hp">
                </div>
                <div class="mb-3">
                    <label class="form-label">
                        Password
                    </label>
                    <div class="input-group input-group-flat">
                        <input type="password" class="form-control" placeholder="Your password" required name="password" id="password" autocomplete="new-password">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary show-password">
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
                <div class="mb-2">
                    <label class="form-label">
                        Password Confirmation
                    </label>
                    <input type="password" class="form-control" placeholder="Retype your password" required name="password_confirmation" id="password-confirmation" autocomplete="new-password">
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Sign in</button>
                </div>
            </form>
        </div>
    </div>
    <div class="text-center text-muted mt-3">
        Already have account? <a href="{{ route('login') }}" tabindex="-1">Sign in</a>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(function () {
        $('.show-password').click(function () {
            var $this = $(this),
                $password = $this.closest('.input-group').find('#password');
                $passwordConfirmation = $('#password-confirmation');

            if ($password.attr('type') === 'password') {
                $password.attr('type', 'text');
                $passwordConfirmation.attr('type', 'text');
                $this.find('svg').removeClass('icon-eye').addClass('icon-eye-off');
            } else {
                $password.attr('type', 'password');
                $passwordConfirmation.attr('type', 'password');
                $this.find('svg').removeClass('icon-eye-off').addClass('icon-eye');
            }
        });
    });
</script>
@endsection
