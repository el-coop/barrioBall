@extends('layouts.app')

@section('title','Welcome')

@section('content')
    @parent
    <div class="container-fluid">
        <div class="hero text-center">
            <h1>@lang('global/welcome.title')</h1>
            <h5>@lang('global/welcome.subtitle')</h5>
        </div>
        <div class="row">
            <div class="col">
                <div class="logo mx-auto text-center">
                    <img src="{{ asset('images/logo.png') }}" class="img-fluid">
                    <h3 class="text-center">Barrio<br>Ball</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row my-3">
            @auth
                <div class="col-12 col-md-4">
                    <a href="{{ action('Match\MatchController@showCreate') }}" class="btn btn-info btn-block btn-lg">
                        @lang('navbar.createLink')
                    </a>
                </div>
                <div class="col-12 col-md-4 mt-1 mt-md-0">
                    <a href="{{ action('Match\MatchController@showSearch') }}" class="btn btn-primary btn-block btn-lg">
                        @lang('navbar.searchLink')
                    </a>
                </div>
                <div class="col-12 col-md-4 mt-1 mt-md-0">
                    <a href="{{ action('UserController@show') }}" class="btn btn-outline-dark btn-block btn-lg">
                        @lang('navbar.profileLink')
                    </a>
                </div>
            @else
                <div class="col-12 col-md-6">
                    <a href="{{ action('Auth\LoginController@showLoginForm') }}" class="btn btn-primary btn-block btn-lg">
                        @lang('navbar.loginLink')
                    </a>
                </div>
                <div class="col-12 col-md-6 mt-1 mt-md-0">
                    <a href="{{ action('Auth\RegisterController@showRegistrationForm') }}" class="btn btn-outline-dark btn-block btn-lg">
                        @lang('navbar.registerLink')
                    </a>
                </div>
            @endauth
        </div>
    </div>
    <div class="footer py-2">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-8 mb-2">
                    <div class="row">
                        <div class="col-12">
                            <h6>@lang('global/welcome.links')</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#" class="text-muted">@lang('global/welcome.about')</a></li>
                                <li><a href="{{ url('https://github.com/el-coop/barrioBall') }}"
                                       class="text-muted">@lang('global/welcome.code')</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-3">
                            <ul class="list-unstyled mb-0">
                                <li><a href="#" class="text-muted">@lang('global/welcome.faq')</a></li>
                                <li><a href="#" class="text-muted">@lang('global/welcome.contribute')</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 mb-2">
                    <div class="row">
                        <div class="col-12">
                            <h6>@lang('global/welcome.languages'):</h6>
                        </div>
                    </div>
                    <div class="row">
                        @foreach(config('languages') as $lang => $language)
                            @if($loop->index % ($loop->count/2) == 0)
                                <div class="col-12 col-md-6">
                                    <ul class="list-unstyled mb-0">
                                        @endif
                                        <li>
                                            <a href="{{action ('LanguageController@switchLang', $lang) }}">{{$language}}</a>
                                        </li>
                                        @if($loop->index % ($loop->count/2) == (($loop->count/2) - 1))
                                    </ul>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection