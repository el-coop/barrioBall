@extends('layouts.app')
@section('title',__('navbar.errorsLink'))

@section('content')
    @parent

    <errors-page inline-template
                 delete-url="/admin/errors"
                 :translate="{
                    error: '@lang('admin/errors.error')',
                    user: '@lang('admin/errors.user')',
                    date: '@lang('admin/errors.date')',
                    resolve: '@lang('admin/errors.resolve')'
                 }">
        <div class="container-fluid mb-5 mt-5">
            <mobile-view-parent>
                <mobile-view-child class="row"
                                    icon="fa-server"
                                    btn="btn-danger">
                    <div class="col-12">
                        @include('admin.errors.phpErrors')
                    </div>
                </mobile-view-child>
                <div class="row d-none d-lg-block">
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
                <mobile-view-child class="row"
                                   icon="fa-code"
                                   btn="btn-danger">
                    <div class="col-12">
                        @include('admin.errors.jsErrors')
                    </div>
                </mobile-view-child>
            </mobile-view-parent>
        </div>
    </errors-page>
@stop
