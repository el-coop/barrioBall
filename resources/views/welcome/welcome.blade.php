@extends('layouts.app')

@section('title',__('global/welcome.pageTitle'))

@section('content')
    @parent
    <div class="container-fluid">
        @include('welcome.introduction')
    </div>
    <div class="container">
        <div class="row my-3">
            @auth
                @include('welcome.userButtons')
            @else
                @include('welcome.guestButtons')
            @endauth
        </div>
    </div>
    <div class="footer py-2">
        <div class="container">
            @include('welcome.footer')
        </div>
    </div>
@endsection