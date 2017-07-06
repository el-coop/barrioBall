@extends('layouts.plain')
@section ('title','Profile')


@section('content')
    @include('partials.navbar.authorized')
    <profile-page inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">
                                @lang('auth.Edit')/{{config('app.name')}}
                            </h3>
                        </div>
                        <div class="card-block">
                            <form role="form" method="post" action="/users/{{$user->id}} ">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PATCH">
                                <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label for="email"
                                           class="col-md-4 col-form-label text-md-right"><strong>@lang('auth.email')
                                            :</strong></label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email"
                                               value="{{$user->email}}" required>

                                        @if ($errors->has('email'))
                                            <span class="form-control-feedback">
                                            {{ $errors->first('email') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label for="password"
                                           class="col-md-4 col-form-label text-md-right"><strong>@lang('auth.password')
                                            :</strong></label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password"
                                               required>

                                        @if ($errors->has('password'))
                                            <span class="form-control-feedback">
                                            {{ $errors->first('password') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm"
                                           class="col-md-4 col-form-label text-md-right"><strong>@lang('auth.confirmPassword')
                                            :</strong></label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required>
                                    </div>
                                </div>


                                <div class="form-group row{{ $errors->has('language') ? ' has-danger' : '' }}">
                                    <label for="language"
                                           class="col-md-4 col-form-label text-md-right"><strong>@lang('auth.language')
                                            :</strong></label>

                                    <div class="col-md-6">
                                        <select id="language" name='language' class="form-control">

											<?php

											if($user->language == "en"){
											?>
                                            <option value="en">English</option>
                                            <option value="es">Español</option>

											<?php
											} else {
											?>
                                            <option value="es">Español</option>
                                            <option value="en">English</option>

											<?php  }  ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            @lang('auth.Edit')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @component('partials.components.panel')
                        @slot('title')
                            <h4>Your managed matches</h4>
                        @endslot
                        <datatable
                                url="{{ action('UserController@getMatches')}}"
                                :fields="userMatchFields"
                                ref="matchTable">
                        </datatable>
                    @endcomponent
                </div>
            </div>
        </div>
    </profile-page>
@endsection
