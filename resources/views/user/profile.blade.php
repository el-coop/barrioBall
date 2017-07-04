@extends('layouts.plain')
@section ('title','Profile')


@section('content')
    @include('partials.navbar.authorized')
    <profile-page inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">

                    <form role="form" method="post" action="{{ action('UserController@updateUsername') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">
                                    @lang('profile/update.changeUsername')
                                </h4>
                            </div>
                            <div class="card-block">
                                <div class="form-group row">
                                    <label for="username"
                                           class="col-md-4 col-form-label text-md-right"><strong>@lang('profile/update.Username')
                                            :</strong></label>

                                    <div class="col-md-6">
                                        <input id="username" type="text" class="form-control" name="username"
                                               value="{{$user->username}}" required>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 offset-md-4">

                                        <button type="submit" class="btn btn-primary">
                                            @lang('profile/update.Updateusername')
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <form role="form" method="post" action="{{ action('UserController@updateEmail') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">
                                    @lang('profile/update.changeEmail')
                                </h4>
                            </div>
                            <div class="card-block">
                                <div class="form-group row{{ $errors->has('email') ? ' has-danger' : '' }}">
                                    <label for="email"
                                           class="col-md-4 col-form-label text-md-right"><strong>@lang('profile/update.email')
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

                                <div class="form-group">
                                    <div class="col-md-6 offset-md-4">

                                        <button type="submit" class="btn btn-primary">
                                            @lang('profile/update.updateEmail')

                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <form role="form" method="post" action="{{ action('UserController@updatePassword') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">
                                    @lang('profile/update.changePassword')
                                </h4>
                            </div>
                            <div class="card-block">
                                <div class="form-group row{{ $errors->has('password') ? ' has-danger' : '' }}">
                                    <label for="password"
                                           class="col-md-4 col-form-label text-md-right"><strong>New @lang('profile/update.password')
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
                                           class="col-md-4 col-form-label text-md-right"><strong>@lang('profile/update.confirmPassword')
                                            :</strong></label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control"
                                               name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 offset-md-4">

                                        <button type="submit" class="btn btn-primary">
                                            @lang('profile/update.updatePassword')
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form role="form" method="post" action="{{ action('UserController@updateLanguage') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PATCH">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">
                                    @lang('profile/update.changeLanguage')
                                </h4>
                            </div>
                            <div class="card-block">


                                <div class="form-group row{{ $errors->has('language') ? ' has-danger' : '' }}">
                                    <label for="language"
                                           class="col-md-4 col-form-label text-md-right"><strong>@lang('profile/update.language')
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
                                            @lang('profile/update.updateLanguage')
                                        </button>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                    <form role="form" method="post" action="/user ">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <div class="card">
                            <div class="card-header alert-danger">
                                <h4 class="text-center">
                                    @lang('profile/update.deleteAccount')
                                </h4>
                            </div>
                            <div class="card-block">


                                <div class="form-group">
                                    <div class="col-md-6 offset-md-4"><p>@lang('profile/update.deleteWarning')
                                            <strong>@lang('profile/update.boldDeleteWarning')</strong></p>


                                        <swal-submit class="btn btn-outline-danger"
                                                     title="@lang('match/show.areYouSure')"
                                                     confirm-text=" @lang('profile/update.deleteYourAccount')"
                                                     cancel-text="No"
                                        ><i class="fa fa-times-circle"></i> @lang('profile/update.deleteYourAccount')
                                        </swal-submit>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>

                <div class="col-md-4 ">
                    <div class="card ">
                        <div class="card-header">
                            <h4 class="text-center">
                                @lang('profile/update.others')
                            </h4>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="/user/matches ">
                                    @lang('profile/update.viewMessages')
                                </a></li>
                        </ul>
                    </div>

                    <br>
                    <div class="card ">
                        <div class="card-header">
                            <h4 class="text-center">
                                @lang('profile/update.latestMatches')
                            </h4>
                        </div>

                        <ul class="list-group">

                            @foreach($user->matches()->orderBy('created_at', 'decs')->first()->take(10)->get() as $match)
                                <li class="list-group-item">
                                    <a href="{{ action('Match\MatchController@showMatch',$match->id) }}">
                                        {{$match->name}}
                                    </a></li>
                            @endforeach
                        </ul>
                    </div>

                    {{--</div>--}}
                </div>
            </div>
        </div>
    </profile-page>
@endsection
@section('scripts')
    @if(Session::has('alert'))
        <script>
            swal({
                title: 'Success',
                text: '{{ Session::get('alert') }}',
                type: 'success',
                timer: 2000
            });
        </script>
    @elseif(count($errors) > 0)
        <script>
            swal({
                title: 'Error',
                text: '{{ $errors->first() }}',
                type: 'error',
                timer: 2000
            });
        </script>
    @endif
@endsection