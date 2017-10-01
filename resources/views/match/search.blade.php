@extends('layouts.app')
@section('title','Search Matches')

@section('content')
    @parent

    <search-page inline-template map-name="@lang('match/search.map')" search-name="@lang('match/search.search')"
                 v-cloak>
        <div class="container-fluid sm-full-height"
             :class="{ 'sm-no-side-padding' : mapToggled, 'fixed-loading' : loading}" id="search-page">
            <flipper :flipped="mapToggled">
                <div class="content-static-right" slot="front">
                    <div class="row mb-4">
                        <div class="col-12 mt-3">
                            @include('match.searchPartials.form')
                        </div>
                    </div>
                    <div class="row mr-md-1">
                        @include('match.searchPartials.results')
                    </div>
                    <div class="row mb-5 mb-md-2" v-if="pages">
                        <div class="col-12 d-flex">
                            <paginate
                                    :click-handler="getPage"
                                    :page-count="pages"
                                    prev-text="@lang('match/search.prev')"
                                    next-text="@lang('match/search.next')"
                                    container-class="pagination mx-auto"
                                    prev-class="page-item"
                                    prev-link-class="page-link"
                                    page-class="page-item"
                                    page-link-class="page-link"
                                    next-class="page-item"
                                    next-link-class="page-link">
                            </paginate>
                        </div>
                    </div>
                </div>
                <div class="map-static-left" slot="back">
                    <leaflet-map ref="map">

                    </leaflet-map>
                </div>
            </flipper>
            <div class="search-toggle text-center">
                <span class="btn-group d-md-none">
                    <button class="btn btn-primary" @click="toggleMap">@{{mapBtn}}</button>
                </span>
            </div>
        </div>
    </search-page>
@endsection