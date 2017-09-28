@extends('layouts.plain')

@section('content')
    @if($user)
        @include('partials.navbar.authorized')
    @else
        @include('partials.navbar.unauthorized')
    @endif
@endsection
