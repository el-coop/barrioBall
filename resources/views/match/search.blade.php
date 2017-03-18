@extends('layouts.plain')
@section('title','Search Matches')

@section('content')
    @include('navbar.authorized')

    <div class="container-fluid">
        <div id="search-results-container">
            <div class="row">
                <div class="col-sm-12">
                    <form class="form-inline text-center">
                        <div class="form-group">
                            <date-picker label="Date:"></date-picker>
                        </div>
                        <div class="form-group">
                            <time-picker label="From:"></time-picker>
                            <time-picker  label="To:"></time-picker>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-info btn-block">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="search-map-container">
            <leaflet-map>

            </leaflet-map>
        </div>
    </div>
    </div>
@endsection