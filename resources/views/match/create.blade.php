@extends('layouts.plain')
@section('title','Create Match')

@section('content')
    @include('partials.navbar.authorized')
    <create-page inline-template
                 map-name="@lang('match/search.map')"
                 search-name="@lang('match/create.create')"
                 init-lng="{{ old('lng') }}"
                 init-lat="{{ old('lat') }}"
                 init-address="{{ old('address') }}"
                 :translate="{
                    'confirmAddress': '@lang('match/create.confirmAddress')'
                 }"
                 v-cloak>
        <div class="container-fluid sm-full-height"
             :class="{ 'sm-no-side-padding' : mapToggled}">
            <flipper :flipped="mapToggled">
                <div class="content-static-right" slot="front">
                    <div class="row mb-4 mt-3">
                        <div class="col-12 col-md-10 mx-auto">
                            <h3 class="text-center">
                                @lang('match/create.create')
                            </h3>
                            <form method="post">
                                {{ csrf_field() }}
                                @if ($errors->has('lat') || $errors->has('lng'))
                                    <div class="alert alert-danger">
                                        @lang('match/create.coordinateError')
                                    </div>
                                @endif
                                <input name="lat" id="lat" v-model="lat" hidden value="{{ old('lat') }}">
                                <input name="lng" id="lng" v-model="lng" hidden value="{{ old('lng') }}">
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="name">@lang('match/create.name')</label>
                                        <input type="text" id="name" name="name"
                                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               value="{{ old('name') }}" required>
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('name') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div v-if="! lat" class="text-center">
                                    <span v-if="! calcAddress">
                                        <h3 class="d-inline align-middle">
                                            @lang('match/create.chooseOnMap')
                                        </h3>
                                        <i class="fa fa-info-circle"
                                           title="@lang('match/create.locationTooltip')"
                                           data-trigger="click"
                                           data-toggle="tooltip"></i>
                                    </span>
                                    <i class="fa fa-spin fa-spinner fa-3x" v-else></i>
                                </div>
                                <div class="form-row" v-else>
                                    <div class="form-group col-12{{ $errors->has('address') ? ' is-invalid' : '' }}">
                                        <label for="address">@lang('match/create.address')</label>
                                        <div class="input-group">
                                            <input type="text" id="address" name="address" v-model="address"
                                                   value="{{ old('address') }}"
                                                   class="form-control" required>
                                            <span class="input-group-addon">
                                                <i class="fa fa-spinner fa-spin" v-if="calcAddress"></i>
                                                <i class="fa fa-check" v-else></i>
                                            </span>
                                        </div>
                                        @if ($errors->has('address'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('address') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-12 col-md-6{{ $errors->has('date') ? ' is-invalid' : '' }}">
                                        <date-picker label="@lang('match/create.date'):" name="date"
                                                     init-value="{{ old('date') }}"></date-picker>
                                        @if ($errors->has('date'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('date') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-12 col-md-6{{ $errors->has('time') ? ' is-invalid' : '' }}">
                                        <time-picker label="@lang('match/create.startTime'):" name="time"
                                                     init-value="{{ old('date') }}"></time-picker>
                                        @if ($errors->has('time'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('time') }}
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-group col-12">
                                        <label>@lang('match/create.players')</label>
                                        <select name="players" id="players"
                                                class="form-control{{ $errors->has('players') ? ' is-invalid' : '' }}"
                                                required>
                                            @for ($i = 8; $i<23; $i+=2)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                        @if ($errors->has('players'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('players') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-12">
                                        <label for="description">@lang('match/create.description')</label>
                                        <textarea id="description" name="description"
                                                  class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}"
                                                  required>{{ old('description') }}</textarea>
                                        @if ($errors->has('description'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('description') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="map-static-left" slot="back">
                    <leaflet-map ref="map"
                                 @right-click="choosenLocation"
                                {{ old('lat') && old('lng') ? ':init-markers=[[' . old('lat') . ',' . old('lng') . ']]' : '' }}
                            {{ old('lat') && old('lng') ? ':center=[' . old('lat') . ',' . old('lng') . ']' : '' }}
                            {{ old('lat') && old('lng') ? ':zoom=15' : '' }}>
                    </leaflet-map>
                </div>
            </flipper>
            <div class="search-toggle text-center">
                <span class="btn-group d-md-none">
                    <button class="btn btn-primary" @click="toggleMap">@{{mapBtn}}</button>
                </span>
            </div>
        </div>
    </create-page>
@endsection