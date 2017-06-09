@extends('layouts.plain')

@section('title','Reset Password')

@section('content')
    @include ('partials.navbar.unauthorized')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>
                            @lang('passwords.resetPassword')
                        </h3>
                    </div>
                    <div class="card-block">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ action('Auth\ResetPasswordController@reset') }}">
                            {{ csrf_field() }}

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label for="email" class="col-md-4 col-form-label text-md-right">@lang('auth.email')</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                           value="{{ $email or old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
                                <label for="password" class="col-md-4 col-form-label text-md-right">@lang('auth.password')</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-damger' : '' }}">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">@lang('auth.confirmPassword')</label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>

                                    @if ($errors->has('password_confirmation'))
                                        <div class="form-control-feedback">
                                            {{ $errors->first('password_confirmation') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('passwords.resetPassword')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
