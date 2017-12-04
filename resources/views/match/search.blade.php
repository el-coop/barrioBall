@extends('layouts.app')
@section('title',__('navbar.searchLink'))

@section('content')
    @parent

    <search-page inline-template map-name="@lang('match/search.map')" search-name="@lang('match/search.search')"
                 v-cloak>
        <div class="container-fluid sm-full-height"
             :class="{ 'sm-no-side-padding' : mapToggled, 'fixed-loading' : loading}" id="search-page">
            <flipper :flipped="mapToggled">
                <div class="content-static-right" slot="front">
                    @include('match.search.front')
                </div>
                <div class="map-static-left" slot="back">
                    <leaflet-map ref="map">

                    </leaflet-map>
                </div>
            </flipper>
            <div class="search-toggle text-center" :class="{ 'd-none' : hideMapToggle}">
                <button class="btn btn-primary d-md-none" @click="toggleMap">@{{mapBtn}}</button>
            </div>
        </div>
    </search-page>
@endsection