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
            <mobile-view-parent class="row">
                <mobile-view-child class="col-12 col-lg-7 order-4"
                                   icon="fa-futbol-o">

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
                </mobile-view-child>
                <mobile-view-child class="col-12 col-lg-5 order-1"
                                   icon="fa-futbol-o">
                    <div class="card mb-2">
                        @include('user.profile.playedTable')
                    </div>

                    @if($hasManagedMatches)
                        <div class="card">
                            @include('user.profile.managedTable')
                        </div>
                    @endif
                </mobile-view-child>
            </mobile-view-parent>
        </div>
    </profile-page>
@endsection