<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
		window.Laravel = <?= json_encode([
			'csrfToken' => csrf_token(),
		]); ?>
    </script>

    @yield('head')
</head>
<body>
<div id="app">
    @yield('content')
</div>

<!-- Scripts -->
<script src="/js/app.js"></script>
@yield('scripts')
</body>
</html>
