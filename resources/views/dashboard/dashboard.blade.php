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
            <mobile-view-parent class="row">
                <mobile-view-child class="col-12 col-lg-6"
                                   icon="fa-futbol-o">
                    @include('dashboard.nextMatchAlert')
                    <div class="card mb-2">
                        @include('user.profile.playedTable')
                        <div class="card-footer text-center">
                            <a href="{{ action('Match\MatchController@showSearch') }}">
                                <button class="btn btn-lg btn-primary">@lang('global/dashboard.find')</button>
                            </a>
                        </div>
                    </div>
                </mobile-view-child>
                <mobile-view-child class="col-12 col-lg-6"
                                   icon="fa-cog">
                    @include('dashboard.requestsAlert')

                    <div class="card mb-2">
                        @include('user.profile.managedTable')
                        <div class="card-footer text-center">
                            <a href="{{ action('Match\MatchController@showCreate') }}">
                                <button class="btn btn-lg btn-outline-primary">@lang('navbar.createLink')</button>
                            </a>
                        </div>
                    </div>
                </mobile-view-child>
            </mobile-view-parent>
        </div>
    </profile-page>
@endsection