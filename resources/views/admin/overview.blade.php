@extends('layouts.app')
@section('title',__('navbar.adminOverview'))

@section('content')
    @parent

    <admin-overview-page inline-template
                         @include('partials.swal')
                         :translate="{
                    username: '@lang('auth.username')',
                    email: '@lang('auth.email')',
                    name: '@lang('match/create.name')',
                    time: '@lang('match/create.startTime')',
                    view: '@lang('profile/page.view')',
                    makeAdmin: '@lang('admin/overview.makeAdmin')'
                 }">
        <div class="container">
            <mobile-view-parent class="row">
                @if($errorsCount > 0)
                    <mobile-view-child class="col-12"
                                       icon="fa-bug"
                                        btn="btn-danger">
                        @include('admin.overview.errorsCount')
                    </mobile-view-child>
                @endif
                <mobile-view-child class="col-12 col-lg-6"
                                   icon="fa-user">
                    @include('admin.overview.usersCount')
                    <div class="card mb-2">
                        <div class="card-footer text-center">
                            @include('admin.overview.userTable')
                        </div>
                    </div>
                </mobile-view-child>
                <mobile-view-child class="col-12 col-lg-6"
                                   icon="fa-futbol-o">
                    @include('admin.overview.matchCount')
                    <div class="card mb-2">
                        <div class="card-footer text-center">
                            @include('admin.overview.matchTable')
                        </div>
                    </div>
                </mobile-view-child>
            </mobile-view-parent>
        </div>
    </admin-overview-page>
@stop
