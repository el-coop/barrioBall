@extends('layouts.app')

@section('title',__('navbar.contactLink'))

@section('content')
    @parent
    <div class="container-fluid">
        <div class="hero text-center">
            <h1>@lang('navbar.contactLink')</h1>
        </div>
    </div>

    <div class="footer py-2">
        <div class="container">
            @include('welcome.footer')
        </div>
    </div>
@endsection