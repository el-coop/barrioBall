@extends('layouts.plain')
@section('title','Errors Table')

@section('content')
    @include('partials.navbar.authorized')

    <errors-page inline-template delete-url="/admin/errors">
        <div class="container-fluid mb-5">
            <div class="row">
                <div class="col-12">
                    @component('partials.components.panel')
                        @slot('title')
                            <h4>PHP Errors</h4>
                        @endslot
                        <datatable
                                url="{{ action('Admin\ErrorController@getPhpErrors')}}"
                                :fields="phpErrorFields"
                                detail-row="php-detail-row"
                                ref="phpTable"
                                delete-class="btn-success"
                                delete-icon="fa-check"
                                @delete="onDelete">
                        </datatable>
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    @component('partials.components.panel')
                        @slot('title')
                            <h4>JS Errors</h4>
                        @endslot
                        <datatable
                                url="{{ action('Admin\ErrorController@getJsErrors') }}"
                                :fields="jsErrorFields"
                                detail-row="js-detail-row"
                                ref="jsTable"
                                delete-class="btn-success"
                                delete-icon="fa-check"
                            @delete="onDelete">
                        </datatable>
                    @endcomponent
                </div>
            </div>
        </div>
    </errors-page>
@stop
