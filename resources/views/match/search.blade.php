@extends('layouts.plain')
@section('title','Search Matches')

@section('content')
    @include('partials.navbar.authorized')

    <search-page inline-template map-name="@lang('search.map')" search-name="@lang('search.search')">
        <div class="container-fluid sm-full-height" :class="{ 'sm-no-side-padding' : mapToggled}" id="search-page">
            <div class="flip-container">
                <div class="flipper" :class="{ flipped: mapToggled}">
                    <div id="search-results-container" class="front">
                        <div class="row bottom-margin">
                            <div class="col-sm-12">
                                <ajax-form class="form-inline text-center" :auto-submit="false" v-on:submit-clicked="submit"
                                           v-on:search-completed="searchResults" ref="form">
                                    <div class="form-group">
                                        <date-picker label="@lang('search.date'):" name="date"></date-picker>
                                    </div>
                                    <div class="form-group">
                                        <time-picker label="@lang('search.from'):" name="start_time"></time-picker>
                                    </div>
                                    <div class="form-group">
                                        <time-picker label="@lang('search.to'):" name="end_time"></time-picker>
                                    </div>
                                    <i class="glyphicon glyphicon-search" slot="submit"></i>
                                </ajax-form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6" v-for="(match, index) in matches">
                                <div class="panel panel-default" :class="{ selected: selectedResult == index}" :ref="'result' + index" @mouseenter="resultHover(index)" @mouseleave="stopHover(index)">
                                    <div class="panel-heading">@{{ match.name  }}</div>
                                    <div class="panel-body">
                                        <p>
                                            <strong>
                                                <span>@{{ match.address }}</span>
                                                <span class="pull-right">@{{ match.players }} @lang('search.players')</span>
                                            </strong>
                                        </p>
                                        <p>@{{ match.description }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center sm-margin-bottom" v-if="pages">
                            <paginate
                                :click-handler="getPage"
                                :page-count="pages"
                                prev-text="@lang('search.prev')"
                                next-text="@lang('search.next')"
                                container-class="pagination">
                            </paginate>
                        </div>
                    </div>
                    <div id="search-map-container" class="back">
                        <leaflet-map ref="map">

                        </leaflet-map>
                    </div>
                </div>
            </div>
            <div class="search-toggle text-center">
                <span class="btn-group visible-xs-inline-block visible-sm-inline-block">
                    <button class="btn btn-primary" @click="toggleMap">@{{mapBtn}}</button>
                </span>
            </div>
        </div>
    </search-page>
@endsection