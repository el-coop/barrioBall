@extends('layouts.app')
@section('title',__('navbar.loginLink'))


@section('content')
    @parent
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header text-center">
                            @lang('auth.login')|{{config('app.name')}}
                    </div>
                    <div class="card-block pt-3 container-fluid">
                        <form role="form" method="POST" action="{{ action('Auth\LoginController@login') }}">
                            {{ csrf_field() }}

                            <div class="form-group row">
                                <label for="email"
                                       class="col-12 col-md-4 col-form-label text-md-right"><strong>@lang('auth.email'):</strong></label>

                                <div class="col-12 col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                           value="{{ old('email') }}" required autofocus>
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
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
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-4 mx-md-auto">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input"
                                                   name="remember" {{ old('remember') ? 'checked' : ''}}>
                                            @lang('auth.rememberMe')
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-4 mx-md-auto">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('auth.login')
                                    </button>

                                    <a class="btn btn-link" href="{{ action('Auth\ResetPasswordController@reset') }}">
                                        Reset Password
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-right">@lang('auth.notMemberYet')?
                        <a href="{{action ('Auth\RegisterController@showRegistrationForm')}}">@lang('auth.register')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
