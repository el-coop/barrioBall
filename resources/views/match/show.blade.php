@extends('layouts.app')
@section('title',$match->name)

@section('content')
    @parent
    <show-page inline-template
            @include('partials.swal')
    >
        <div class="container">
            <div class="row mt-2">
                <div class="col-12 col-md-6">
                    @include('match.show.nameAndJoin')
                </div>
                <div class="col-12 col-md-6 text-md-right d-md-flex flex-column justify-content-center">
                    @manager($match)
                    @include('match.show.inviteManagers')
                    @include('match.show.stopManaging')
                    @include('match.show.deleteMatch')
                    @endmanager
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <hr>
                    <div class="row">
                        @include('match.show.details')
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            @include('match.show.description')
                        </div>
                        <div class="col-12 col-md-4">
                            @include('match.show.playersAndRequests')
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row">
                @include('match.show.map')
            </div>
        </div>
    </show-page>
@endsection