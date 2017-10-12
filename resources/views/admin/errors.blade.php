@extends('layouts.app')
@section('title','Errors Table')

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
            <div class="row">
                <div class="col-12">
                    @include('admin.errors.phpErrors')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @include('admin.errors.jsErrors')
                </div>
            </div>
        </div>
    </errors-page>
@stop
