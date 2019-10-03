<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>wbg.ch</title>
<meta name="csrf-token" value="{{ csrf_token() }}" />
<meta name="format-detection" content="telephone=no">
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
<link href="{{ asset('assets/css/app.css') }}" type="text/css" rel="stylesheet" />
</head>
<body>
<header class="site-header">

</header>

<main class="site-content" role="main">
@yield('content')
</main>

<footer class="site-footer">

</footer>

<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>
</body>
<!-- made with ❤ by marceli.to -->
</html>