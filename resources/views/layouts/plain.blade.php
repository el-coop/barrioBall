<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <!-- Scripts -->
    <script>
		window.Laravel = @json([
			'csrfToken' => csrf_token(),
            'locale' => App::getLocale()
		])
    </script>

    @yield('head')
</head>
<body>
<div id="app">
    @yield('content')

    @auth
        <echo :user="{{ $user->id }}">
        </echo>
    @endauth
</div>

<!-- Scripts -->
<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
@yield('scripts')
</body>
</html>