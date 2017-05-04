@extends('layouts.plain')
@section('title','Search Matches')

@section('content')
    @include('partials.navbar.authorized')

    <search-page inline-template map-name="@lang('search.map')" search-name="@lang('search.search')">
        <div class="container-fluid sm-full-height"
             :class="{ 'sm-no-side-padding' : mapToggled, 'fixed-loading' : loading}" id="search-page">
            <flipper :flipped="mapToggled">
                <div class="content-static-right" slot="front">
                    <div class="row mb-4">
                        <div class="col-12 mt-2">
                            <ajax-form class="form-inline justify-content-center" :auto-submit="false"
                                       v-on:submit-clicked="submit"
                                       v-on:search-completed="searchResults" v-on:error="showErrors" :errors-box="false"
                                       btn-wrapper-class="w-100-sm-down align-self-baseline"
                                       ref="form">
                                <div class="mb-2 mr-sm-2 mb-sm-0 w-100-sm-down align-self-baseline"
                                     :class="{'has-danger' : errors.hasOwnProperty('date')}"
                                     id="date_group">
                                    <date-picker label="@lang('search.date'):" name="date"></date-picker>
                                    <span class="form-control-feedback"
                                          v-if="errors.hasOwnProperty('date')">* @{{ errors.date[0] }}</span>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 w-100-sm-down align-self-baseline"
                                     :class="{'has-danger' : errors.hasOwnProperty('from')}"
                                     id="start_time_group">
                                    <time-picker label="@lang('search.from'):" name="from"></time-picker>
                                    <span class="form-control-feedback"
                                          v-if="errors.hasOwnProperty('from')">* @{{ errors.from[0] }}</span>
                                </div>
                                <div class="mb-2 mr-sm-2 mb-sm-0 w-100-sm-down align-self-baseline" :class="{'has-danger' : errors.hasOwnProperty('to')}"
                                     id="end_time_group">
                                    <time-picker label="@lang('search.to'):" name="to"></time-picker>
                                    <span class="form-control-feedback"
                                          v-if="errors.hasOwnProperty('to')">* @{{ errors.to[0]}}</span>
                                </div>
                                <i class="fa fa-search" slot="submit"></i>
                            </ajax-form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" v-if="matches != null && !matches.length">
                            <h4 class="text-center">
                                @lang('search.noMatchesFound')
                            </h4>
                        </div>
                        <div class="col-12 col-md-6 mb-3" v-for="(match, index) in matches">
                            <div class="card" :class="{ selected: selectedResult == index}"
                                 :ref="'result' + index" @mouseenter="resultHover(index)"
                                 @mouseleave="stopHover(index)">
                                <div class="card-header bg-white">
                                    @{{ match.name  }}
                                    <span class="pull-right">@{{ match.date }} @{{ match.time }}</span>
                                </div>
                                <div class="card-block search-result-map-wrapper">
                                    <div class="search-result-map">
                                        <leaflet-map :interactive="false" :zoom="19" :center="[match.lat,match.lng]">
                                        </leaflet-map>
                                        <div class="row">

                                            <div class="col-7"><strong>@{{ match.address }}</strong></div>
                                            <div class="col-5 text-right">
                                                <strong>@{{ match.players }} @lang('search.players')</strong></div>
                                            <div class="col-12">
                                                <p>@{{ match.description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-5 mb-md-2" v-if="pages">
                        <div class="col-12 d-flex">
                            <paginate
                                    :click-handler="getPage"
                                    :page-count="pages"
                                    prev-text="@lang('search.prev')"
                                    next-text="@lang('search.next')"
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
                <span class="btn-group hidden-md-up">
                    <button class="btn btn-primary" @click="toggleMap">@{{mapBtn}}</button>
                </span>
            </div>
        </div>
    </search-page>
@endsection