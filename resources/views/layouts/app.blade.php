<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>{{ config('app.name') }} - @yield('title')</title>
    <!-- CSS files -->
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon"/>
    <link href="{{ asset('tabler/dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/demo.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/jquery/datatables.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }} ">
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote.min.css') }} ">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <style>
        @import url('https://rsms.me/inter/inter.css');
        .pagination {
            display: flex;
            float: right;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #0d6efd;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-link:hover {
            z-index: 2;
            color: #0a58ca;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .page-link:focus {
            z-index: 3;
            color: #0a58ca;
            background-color: #e9ecef;
            border-color: #dee2e6;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .page-item:not(:first-child) .page-link {
            margin-left: 0;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #fff;
            border-color: #dee2e6;
        }
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        i{
            margin-right: 5px;
        }


        .select2-container.select2-container--default.select2-container--open{
            z-index: 99999999;
        }

        .select2.select2-container.select2-container--default{
            width: 100% !important;
        }

        .accordion-button:after {
            opacity: .7;
            color: white;
        }

        .select2-selection--single{
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            min-height: 35px;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-select: none;
            outline: none;
            background-color: #fdfdff;
            border-color: #e4e6fc;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            min-height: 35px;
            line-height: 35px;
            /* padding-left: 20px;
            padding-right: 20px; */
        }

        .label-required:after {
            content: " *";
            color: red;
        }

        select[readonly].select2-hidden-accessible + .select2-container {
            pointer-events: none;
            touch-action: none;
            opacity:0.6;
            cursor:no-drop;
        }
    </style>

    @yield('styles')
</head>

<body class=" layout-fluid">
    <script src="{{ asset('tabler/dist/js/demo-theme.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <div class="page">
        <!-- Sidebar -->
        @include('components.sidebar')
        <div class="page-wrapper">
            @yield('content')
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; {{ date('Y') }}
                                    <a href="{{ route('home') }}" class="link-secondary">{{ env('APP_NAME') }}</a>.
                                    All rights reserved.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ asset('tabler/dist/js/tabler.min.js') }}"></script>
    <script src="{{ asset('tabler/dist/js/demo.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/robinherbots/inputmask.min.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/summernote/summernote.min.js') }}"></script>
    <script type="text/javascript">
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

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });

        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch (type) {
            case 'info':
                Toast.fire({
                    icon: 'info',
                    title: '{{ Session::get('message') }}'
                });
                break;

            case 'warning':
                Toast.fire({
                    icon: 'warning',
                    title: '{{ Session::get('message') }}'
                });
                break;

            case 'success':
                Toast.fire({
                    icon: 'success',
                    title: '{{ Session::get('message') }}'
                });
                break;

            case 'error':
                Toast.fire({
                    icon: 'error',
                    title: '{{ Session::get('message') }}'
                });
                break;
        }
        @endif

    </script>
    @yield('scripts')
</body>

</html>
