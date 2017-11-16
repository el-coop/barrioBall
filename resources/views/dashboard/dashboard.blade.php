@extends('layouts.app')

@section('title',__('global/dashboard.pageTitle'))

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
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6">
                    @include('dashboard.nextMatchAlert')
                    <div class="card mb-2">
                        @include('user.profile.playedTable')
                        <div class="card-footer text-center">
                            <a href="{{ action('Match\MatchController@showSearch') }}">
                                <button class="btn btn-lg btn-primary">@lang('global/dashboard.find')</button>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    @include('dashboard.requestsAlert')

                    <div class="card mb-2">
                        @include('user.profile.managedTable')
                        <div class="card-footer text-center">
                            <a href="{{ action('Match\MatchController@showCreate') }}">
                                <button class="btn btn-lg btn-outline-primary">@lang('navbar.createLink')</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </profile-page>
@endsection