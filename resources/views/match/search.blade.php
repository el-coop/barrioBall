@extends('layouts.plain')
@section('title','Search Matches')

@section('content')
    @include('partials.navbar.authorized')

    <search-page inline-template map-name="@lang('search.map')" search-name="@lang('search.search')">
        <div class="container-fluid sm-full-height"
             :class="{ 'sm-no-side-padding' : mapToggled, 'fixed-loading' : loading}" id="search-page">
            <flipper :flipped="mapToggled">
                <div class="content-static-right" slot="front">
                    <div class="row bottom-margin">
                        <div class="col-sm-12">
                            <ajax-form class="form-inline text-center" :auto-submit="false" v-on:submit-clicked="submit"
                                       v-on:search-completed="searchResults" v-on:error="showErrors" :errors-box="false"
                                       ref="form">
                                <div class="form-group" :class="{'has-error' : errors.hasOwnProperty('date')}"
                                     id="date_group">
                                    <date-picker label="@lang('search.date'):" name="date"></date-picker>
                                    <span class="help-block text-left"
                                          v-if="errors.hasOwnProperty('date')">* @{{ errors.date[0] }}</span>
                                </div>
                                <div class="form-group" :class="{'has-error' : errors.hasOwnProperty('from')}"
                                     id="start_time_group">
                                    <time-picker label="@lang('search.from'):" name="from"></time-picker>
                                    <span class="help-block text-left"
                                          v-if="errors.hasOwnProperty('from')">* @{{ errors.from[0] }}</span>
                                </div>
                                <div class="form-group" :class="{'has-error' : errors.hasOwnProperty('to')}"
                                     id="end_time_group">
                                    <time-picker label="@lang('search.to'):" name="to"></time-picker>
                                    <span class="help-block text-left"
                                          v-if="errors.hasOwnProperty('to')">* @{{ errors.to[0]}}</span>
                                </div>
                                <i class="glyphicon glyphicon-search" slot="submit"></i>
                            </ajax-form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" v-if="matches != null && !matches.length">
                            <h4 class="text-center">
                                @lang('search.noMatchesFound')
                            </h4>
                        </div>
                        <div class="col-sm-12 col-md-6" v-for="(match, index) in matches">
                            <div class="panel panel-default" :class="{ selected: selectedResult == index}"
                                 :ref="'result' + index" @mouseenter="resultHover(index)"
                                 @mouseleave="stopHover(index)">
                                <div class="panel-heading">
                                        @{{ match.name  }}
                                        <span class="pull-right">@{{ match.date }} @{{ match.time }}</span>
                                </div>
                                <div class="panel-body">
                                    <div class="search-result-map">
                                        <leaflet-map :interactive="false" :zoom="19" :center="[match.lat,match.lng]">
                                        </leaflet-map>
                                        <div class="row">

                                                <div class="col-xs-7"><strong>@{{ match.address }}</strong></div>
                                                <div class="col-xs-5 text-right"><strong>@{{ match.players }} @lang('search.players')</strong></div>
                                            <div class="col-xs-12">
                                            <p>@{{ match.description }}</p>
                                            </div>
                                        </div>
                                    </div>
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
                <div class="map-static-left" slot="back">
                    <leaflet-map ref="map">

                    </leaflet-map>
                </div>
            </flipper>
            <div class="search-toggle text-center">
                <span class="btn-group visible-xs-inline-block visible-sm-inline-block">
                    <button class="btn btn-primary" @click="toggleMap">@{{mapBtn}}</button>
                </span>
            </div>
        </div>
    </search-page>
@endsection