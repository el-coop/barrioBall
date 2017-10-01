@extends('layouts.plain')

@section('content')
    @auth
        @include('partials.navbar.authorized')
    @else
        @include('partials.navbar.unauthorized')
    @endauth
@endsection
