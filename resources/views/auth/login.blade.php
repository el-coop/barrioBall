@extends('layouts.plain')
@section('title','Login')


@section('content')
    @include ('partials.navbar.unauthorized')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">
                            @lang('auth.login')
                        </h3>
                    </div>
                    <div class="card-block">
                        <form role="form" method="POST" action="{{ action('Auth\LoginController@login') }}">
                            {{ csrf_field() }}

                            <div class="form-group row {{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label for="email"
                                       class="col-md-4 col-form-label text-md-right"><strong>@lang('auth.email'):</strong></label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ old('email') }}" required autofocus>
                                    @if ($errors->has('email'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <label for="password"
                                       class="col-md-4 col-form-label text-md-right"><strong>@lang('auth.password')</strong></label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="form-control-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input"
                                                   name="remember" {{ old('remember') ? 'checked' : ''}}>
                                            @lang('auth.rememberMe')
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 offset-md-4">
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
