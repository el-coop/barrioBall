@extends('layouts.app')
@section('title',__('navbar.adminOverview'))

@section('content')
    @parent

    <admin-overview-page inline-template
                         :translate="{
                    username: '@lang('auth.username')',
                    email: '@lang('auth.email')',
                 }">
        <div class="container">
            @if($errors > 0)
            <div class="row">
                <div class="col-12">
                    @include('admin.overview.errorsCount')
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-12 col-md-6">
                    @include('admin.overview.usersCount')
                    <div class="card mb-2">
                        <div class="card-footer text-center">
                            @include('admin.overview.userTable')
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    @include('admin.overview.matchCount')
                    <div class="card mb-2">
                        <div class="card-footer text-center">
                            @include('admin.overview.matchTable')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </admin-overview-page>
@stop
