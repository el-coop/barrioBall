@extends('layouts.plain')
@section('title',$match->name)

@section('content')
    @include('partials.navbar.authorized')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>
                    {{ $match->name }}
                </h2>
                <p>
                    Managed by:
                    @foreach($match->managers as $manager)
                        <a href="#">{{ $manager->username }}</a>@if (!$loop->last), @endif
                    @endforeach
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <hr>
                <div class="row">
                    <div class="col-12 col-md-4 text-center">
                        <p>
                            <i class="fa fa-home fa-3x"></i>
                        </p>
                        <strong>
                            {{$match->address}}
                        </strong>
                        <hr class="hidden-md-up">
                    </div>
                    <div class="col-12 col-md-4 text-center">
                        <p>
                            <i class="fa fa-clock-o fa-3x"></i>
                        </p>
                        <strong>
                            {{$match->date}} {{$match->time}}
                        </strong>
                        <hr class="hidden-md-up">
                    </div>
                    <div class="col-12 col-md-4 text-center">
                        <p>
                            <i class="fa fa-users fa-3x"></i>
                        </p>
                        <strong>
                            {{$match->registeredPlayers()->count()}}/{{$match->players}}
                        </strong>
                        <p>
                            <button class="btn btn-success"><i class="fa fa-plus"></i> Join request</button>
                        </p>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <p>
                            {{ str_replace('\n','<br>',$match->description) }}
                        </p>
                        <hr class="hidden-md-up">
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="list-group">
                            <span class="list-group-item list-group-item-info"><strong>Players: </strong></span>
                            @foreach($match->registeredPlayers as $player)
                                <a href="#" class="list-group-item">{{$player->username}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col row-map-wrapper">
                <leaflet-map ref="map"
                             :center="[{{$match->lat}},{{$match->lng}}]">

                </leaflet-map>
            </div>
        </div>
    </div>
@endsection