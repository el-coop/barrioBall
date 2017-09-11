@extends('layouts.plain')
@section('title',$match->name)

@section('content')
    @if($user)
        @include('partials.navbar.authorized')
    @else
        @include('partials.navbar.unauthorized')
    @endif
    <show-page inline-template>
        <div class="container">
            <div class="row mt-2">
                <div class="col-12 col-md-6">
                    @include('match.showPartials.nameAndJoin')
                </div>
                <div class="col-12 col-md-6 text-md-right d-md-flex flex-column justify-content-center">
                    @include('match.showPartials.manageOptions')
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                    <div class="row">
                        @include('match.showPartials.details')
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            @include('match.showPartials.description')
                        </div>
                        <div class="col-12 col-md-4">
                            @include('match.showPartials.playersAndRequests')
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row">
                @include('match.showPartials.map')
            </div>
        </div>
    </show-page>
@endsection

@section('scripts')
    @include('partials.swal')
@endsection