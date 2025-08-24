<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon" />
    <!-- CSS files -->
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/css/intlTelInput.css">
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
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

        .iti.iti--allow-dropdown{
            width: 100% !important;
        }

    </style>

    @yield('styles')
</head>

<body class="">
    <script src="{{ asset('tabler/dist/js/demo-theme.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <div class="page">
        <div class="page-wrapper">
            <div class="page-body">
                @yield('content')
            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/intlTelInput.min.js"></script>
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
