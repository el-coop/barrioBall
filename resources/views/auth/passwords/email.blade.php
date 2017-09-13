@extends('layouts.plain')

@section('title','Reset Password')

@section('content')
    @include ('partials.navbar.unauthorized')
    <div class="container my-5">
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>
                            @lang('passwords.resetPassword')
                        </h3>
                    </div>
                    <div class="card-block pt-3 container-fluid">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ action('Auth\ForgotPasswordController@sendResetLinkEmail') }}">
                            {{ csrf_field() }}

                            <div class="form-group row">
                                <label for="email" class="col-12 col-md-4 col-form-label text-md-right"><strong>@lang('auth.email')</strong></label>

                                <div class="col-12 col-md-6">
                                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                           value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12 col-md-4 mx-md-auto">
                                    <button type="submit" class="btn btn-primary">
                                        @lang('passwords.sendResetPasswordLink')
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
