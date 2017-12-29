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
            <mobile-view-parent class="row" ref="mobileView">
                <mobile-view-child class="col-12 col-lg-4"
                                   icon="fa-list">
                    @include('tech.menu')
                </mobile-view-child>
                <mobile-view-child class="col-12 col-md-8"
                                   icon="fa-info">
                    @include('tech.cards')
                </mobile-view-child>
            </mobile-view-parent>
        </div>
    </tech-page>
    <div class="footer py-2">
        <div class="container">
            @include('welcome.footer')
        </div>
    </div>
@endsection