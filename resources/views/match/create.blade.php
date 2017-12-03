@extends('layouts.app')
@section('title',isset($match) ? __('match/edit.title',['name' => $match->name]) : __('navbar.createLink'))

@section('content')
    @parent
    <create-page inline-template
                 map-name="@lang('match/search.map')"
                 search-name="@lang('match/create.create')"
                 init-lng="{{ old('lng', $match->lng ?? '') }}"
                 init-lat="{{ old('lat', $match->lat ?? '') }}"
                 init-address="{{ old('address', $match->address ?? '') }}"
                 :translate="{
                    'confirmAddress': '@lang('match/create.confirmAddress')'
                 }"
                 v-cloak>
        <div class="container-fluid sm-full-height"
             :class="{ 'sm-no-side-padding' : mapToggled}">
            <flipper :flipped="mapToggled">
                <div class="content-static-right" slot="front">
                    @include('match.create.front')
                </div>
                <div class="map-static-left" slot="back">
                    @include('match.create.back')
                </div>
            </flipper>
            <div class="search-toggle text-center">
                <button class="btn btn-primary d-md-none" @click="toggleMap">@{{mapBtn}}</button>
            </div>
        </div>
    </create-page>
@endsection