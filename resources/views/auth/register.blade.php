@extends('layouts.plain')
@section ('title','Register')


@section('content')
    @include('partials.navbar.unauthorized')
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">
                            @lang('auth.register')/{{config('app.name')}}
                        </h3>
                    </div>
                    <div class="card-block pt-3">
                        <form role="form" method="POST" action="{{ url('/register') }}" class="container-fluid">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="username"
                                       class="col-12 col-md-4 col-form-label text-md-right"><strong>@lang('auth.username'):</strong></label>

                                <div class="col-12 col-md-6">
                                    <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username"
                                           value="{{ old('username') }}" required>

                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('username') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                       class="col-12 col-md-4 col-form-label text-md-right"><strong>@lang('auth.email'):</strong></label>

                                <div class="col-12 col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                           value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password"
                                       class="col-12 col-md-4 col-form-label text-md-right"><strong>@lang('auth.password'):</strong></label>

                                <div class="col-12 col-md-6">
                                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm"
                                       class="col-12 col-md-4 col-form-label text-md-right"><strong>@lang('auth.confirmPassword'):</strong></label>

                                <div class="col-12 col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="language"
                                       class="col-12 col-md-4 col-form-label text-md-right"><strong>@lang('auth.language'):</strong></label>

                                <div class="col-12 col-md-6">
                                    <select id="language" name='language' class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}">
                                        @foreach (config('languages') as $lang => $language)
                                            <option value="{{$lang}}"{{ App::getLocale() == $lang ? ' selected' : '' }}>{{$language}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('language'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('language') }}
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group row">
                                <div class="col-12 col-md-4 mx-md-auto">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('auth.register')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer text-right">@lang('auth.alreadyRegistred')
                        <a href="{{action ('Auth\LoginController@showLoginForm')}}">@lang('auth.login')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
