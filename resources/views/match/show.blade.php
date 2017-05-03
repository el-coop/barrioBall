@extends('layouts.plain')
@section('title',$match->name)

@section('content')
    @include('partials.navbar.authorized')

    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2>
                    {{ $match->name }}
                </h2>
                <p>
                    <strong>Address:</strong> {{ $match->address }}
                    <br>
                    <strong>Time:</strong> {{ $match->date }} {{ $match->time }}
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <hr>
                <div class="row">
                    <div class="col-xs-12 col-md-6 text-center">
                        <p>
                            <i class="fa fa-users fa-3x"></i>
                        </p>
                        <p>
                            {{$match->registeredPlayers()->count()}}/{{$match->players}}
                        </p>
                    </div>
                    <div class="col-xs-12 col-md-6 text-center">
                        Players {{$match->players}}
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
@endsection