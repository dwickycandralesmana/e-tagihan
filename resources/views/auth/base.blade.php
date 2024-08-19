<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>{{ config('app.name') }} - @yield('title')</title>
	<!-- CSS files -->
    <link href="{{ asset('tabler/dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('tabler/dist/css/demo.min.css') }}" rel="stylesheet" />
	<style>
		@import url('https://rsms.me/inter/inter.css');

		:root {
			--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
		}

		body {
			font-feature-settings: "cv03", "cv04", "cv11";
		}
	</style>

    @yield('styles')
</head>

<body class=" d-flex flex-column">
	<script src="{{ asset('tabler/dist/js/demo-theme.min.js') }}"></script>
	<div class="page page-center">
		@yield('content')
	</div>
	<!-- Libs JS -->
	<!-- Tabler Core -->
    <script src="{{ asset('tabler/dist/js/tabler.min.js') }}"></script>
    <script src="{{ asset('tabler/dist/js/demo.min.js') }}"></script>
	<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    @yield('scripts')
</body>

</html>
