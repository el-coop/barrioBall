@extends('layouts.app')
@section ('title',__('navbar.profileLink'))


@section('content')
    @parent
    <profile-page inline-template
                  @include('partials.swal')
                  :titles="{
                    name: '@lang('match/create.name')',
                    view: '@lang('profile/page.view')',
                    requests: '@lang('profile/page.requests')',
                    date: '@lang('match/search.date')',
                  }">
        <div class="container mb-5 mt-5">
            <div class="row">
                <div class="col-md-5">
                    <div class="card mb-2">
                        @include('user.profile.playedTable')
                    </div>

                    @if($user->managedMatches()->count())
                        <div class="card">
                            @include('user.profile.managedTable')
                        </div>
                    @endif
                </div>

                <div class="col-12 col-md-7">
                    <div>
                        @include('user.profile.updateUsername')
                    </div>
                    <div class="mt-5">
                        @include('user.profile.updateEmail')
                    </div>
                    <div class="mt-5">
                        @include('user.profile.updatePassword')
                    </div>
                    <div class="mt-5">
                        @include('user.profile.updateLanguage')
                    </div>
                    <div class="mt-5">
                        @include('user.profile.deleteUser')
                    </div>
                </div>
            </div>
        </div>
    </profile-page>
@endsection