@extends('layouts.plain')
@section('title','Create Match')

@section('content')
    @include('partials.navbar.authorized')
    <div class="container" id="app">
        <h3 class="text-center">
            @lang('create.create')
        </h3>
        <form method="post" role="form" class="">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="name">@lang('create.name')</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <div class="form-group">
                <button type="button" id="mapBtn" class="btn btn-primary" data-toggle="modal"
                        data-target="#mapModal">@lang('create.map')
                </button>
                <input name="lat" id="lat" type="hidden">
                <input name="lng" id="lng" type="hidden">
                <mapel></mapel>
            </div>
            <div class="form-group">
                <label for="address">@lang('create.address')</label>
                <input type="text" id="address" name="address" id="address" class="form-control">
            </div>
            <div class="form-group">
                <date-picker label="@lang('create.date'):" name="date"></date-picker>
            </div>
            <div class="form-group">
                <time-picker label="@lang('create.from'):" name="time"></time-picker>
            </div>
            <div class="form-group">
                <label for="public">@lang('create.public')<input id="public" class="form-control" type="checkbox"
                                                                 data-toggle="switch" value="true"
                                                                 name="public"></label>
            </div>
            <div class="form-group">
                <label>@lang('create.players')</label>
                <select name="players" id="players" class="form-control">
                    @for ($i = 8; $i<23; $i+=2)
                        <option value="{{$i}}">{{$i}}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group">
                <label for="description">@lang('create.description')</label>
                <textarea id="description" name="description" style="width: 100%"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>
        </form>
        <div>

        </div>
    </div>
@endsection