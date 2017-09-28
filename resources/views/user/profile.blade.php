@extends('layouts.app')
@section ('title','Profile')


@section('content')
    @parent
    <profile-page inline-template
                  :titles="{
                    name: '@lang('match/create.name')',
                    view: '@lang('profile/page.view')',
                    requests: '@lang('profile/page.requests')'

                  }">
        <div class="container mb-5 mt-5">
            <div class="row">
                <div class="col-md-5">
                    <div class="card mb-2">
                        @component('partials.components.panel')
                            @slot('title')
                                <h4>@lang('profile/page.playedMatches')</h4>
                            @endslot
                            <datatable
                                    url="{{ action('UserController@getMatches')}}"
                                    :fields="playedMatchesFields"
                                    :inline-forms="false"
                                    :per-page-options="[5,10]"
                                    class="mt-3">
                            </datatable>
                        @endcomponent
                    </div>

                    @if($user->managedMatches()->count() > 0)
                        <div class="card">
                            @component('partials.components.panel')
                                @slot('title')
                                    <h4>@lang('profile/page.mangedMatches')</h4>
                                @endslot
                                <datatable
                                        url="{{ action('UserController@getMatches')}}"
                                        :fields="managedMatchesFields"
                                        :inline-forms="false"
                                        :per-page-options="[5,10]"
                                        :extra-params="{
                                        managed: true
                                    }"
                                        class="mt-3">
                                </datatable>
                            @endcomponent
                        </div>
                    @endif
                </div>

                <div class="col-12 col-md-7">
                    <form method="post" action="{{ action('UserController@updateUsername') }}">
                        {{ csrf_field() }}
                        {{ method_field('patch') }}
                        <h4 class="">
                            @lang('profile/page.changeUsername')
                        </h4>
                        <hr>
                        <div class="form-group">
                            <label for="username">
                                <strong>@lang('profile/page.newUsername'):</strong>
                            </label>

                            <input id="username" type="text" class="form-control" name="username"
                                   value="{{$user->username}}" required>


                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                @lang('profile/page.updateUsername')
                            </button>
                        </div>
                    </form>

                    <form method="post" action="{{ action('UserController@updateEmail') }}">
                        {{ csrf_field() }}
                        {{ method_field('patch') }}
                        <h4>
                            @lang('profile/page.changeEmail')
                        </h4>
                        <hr>
                        <div class="form-group">
                            <label for="email">
                                <strong>@lang('profile/page.email'):</strong>
                            </label>

                            <input id="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                                   value="{{$user->email}}" required>

                            @if ($errors->has('email'))
                                <span class="invlid-feedback">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                @lang('profile/page.updateEmail')
                            </button>
                        </div>
                    </form>

                    <form method="post" action="{{ action('UserController@updatePassword') }}">
                        {{ csrf_field() }}
                        {{ method_field('patch') }}
                        <h4>
                            @lang('profile/page.changePassword')
                        </h4>
                        <hr>
                        <div class="form-group">
                            <label for="password">
                                <strong>New @lang('profile/page.password'):</strong>
                            </label>
                            <input id="password" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   name="password"
                                   required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">
                                <strong>@lang('profile/page.confirmPassword'):</strong>
                            </label>
                            <input id="password-confirm" type="password" class="form-control"
                                   name="password_confirmation" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                @lang('profile/page.updatePassword')
                            </button>
                        </div>
                    </form>
                    <form role="form" method="post" action="{{ action('UserController@updateLanguage') }}">
                        {{ csrf_field() }}
                        {{ method_field('patch') }}
                        <h4>
                            @lang('profile/page.changeLanguage')
                        </h4>
                        <hr>

                        <div class="form-group">
                            <label for="language">
                                <strong>@lang('profile/page.language'):</strong>
                            </label>


                            <div class="form-group">
                                <select id="language" name='language'
                                        class="form-control{{ $errors->has('language') ? ' is-invalid' : '' }}">
                                    @foreach (config('languages') as $lang => $language)
                                        <option value="{{$lang}}"{{ $user->language == $lang ? ' selected' : ''}}>{{ $language }}</option>
                                    @endforeach
                                </select>

                                @if ($errors->has('language'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('language') }}
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    @lang('profile/page.updateLanguage')
                                </button>
                            </div>
                        </div>

                    </form>
                    <form method="post" action="{{ action('UserController@deleteUser') }}">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <h4>
                            @lang('profile/page.deleteAccount')
                        </h4>
                        <hr>

                        <div class="form-group">
                            <p>@lang('profile/page.deleteWarning')</p>

                            <swal-submit class="btn btn-outline-danger"
                                         title="@lang('match/show.areYouSure')"
                                         confirm-text=" @lang('profile/page.deleteYourAccount')"
                                         cancel-text="No"
                            ><i class="fa fa-times-circle"></i> @lang('profile/page.deleteYourAccount')
                            </swal-submit>

                        </div>

                    </form>
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