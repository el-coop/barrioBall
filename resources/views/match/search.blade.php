@extends('layouts.plain')
@section('title','Search Matches')

@section('content')
    @include('navbar.authorized')

    <search-page inline-template>
        <div class="container-fluid">
            <div id="search-results-container">
                <div class="row">
                    <div class="col-sm-12">
                        <ajax-form class="form-inline text-center" :auto-submit="false" v-on:submit-clicked="submit" ref="form">
                            <div class="form-group">
                                <date-picker label="Date:" name="date"></date-picker>
                            </div>
                            <div class="form-group">
                                <time-picker label="From:" name="start_time"></time-picker>
                                <time-picker label="To:" name="end_time"></time-picker>
                            </div>
                            <i class="glyphicon glyphicon-search" slot="submit"></i>
                        </ajax-form>
                    </div>
                </div>
            </div>
            <div id="search-map-container">
                <leaflet-map ref="map">

                </leaflet-map>
            </div>
        </div>
    </search-page>
@endsection