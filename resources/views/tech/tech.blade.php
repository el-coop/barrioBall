@extends('layouts.app')

@section('title',__('global/welcome.tech'))

@section('content')
    @parent
    <div class="container-fluid">
        <div class="hero text-center">
            <h1>@lang('global/tech.title')</h1>
        </div>
    </div>
    <tech-page inline-template
                active="@lang('global/tech.serverSide')">
        <div class="container mb-4">
            <div class="row">
                <div class="col-12 col-md-4">
                    @include('tech.menu')
                </div>
                <div class="col-12 col-md-8">
                    @include('tech.cards')
                </div>
            </div>
        </div>
    </tech-page>
    <div class="footer py-2">
        <div class="container">
            @include('welcome.footer')
        </div>
    </div>
@endsection