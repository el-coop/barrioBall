@extends('layouts.plain')
@section('title',$match->name)

@section('content')
    @include('partials.navbar.authorized')

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <h2>
                    {{ $match->name }}
                </h2>
                @lang('match/show.managedBy'):
                @foreach($match->managers as $manager)
                    <a href="#">{{ $manager->username }}</a>@if (!$loop->last), @endif
                @endforeach
                @if($canJoin)
                    <form method="post" action="{{ action('Match\MatchUsersController@joinMatch', $match) }}"
                          class="mt-1 mb-1">
                        {{ csrf_field() }}
                        <button class="btn btn-success col-12 col-md-3"><i class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')
                        </button>
                    </form>
                @elseif($user && $user->inMatch($match))
                    <form method="post" action="{{ action('Match\MatchUsersController@leaveMatch', $match) }}"
                          class="mb-1">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="btn btn-warning col-12 col-md-3"><i class="fa fa-minus-circle"></i> @lang('match/show.leaveMatch')
                        </button>
                    </form>
                @endif
            </div>
            <div class="col-12 col-md-6 text-md-right">
                @if($user && $user->isManager($match))
                    <modal v-cloak btn-class="col-12 col-md-4 btn-info mb-1">
                        <span slot="button">
                            <i class="fa fa-plus-circle"></i> Invite Managers
                        </span>
                        <form method="post" action="{{ action('Match\MatchUsersController@inviteManagers', $match) }}"
                              slot="body">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <multi-select name="invite_managers" label="name"
                                              action="/matches/users"></multi-select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-info btn-block"><i class="fa fa-plus-circle"></i> @lang('match/show.inviteManagers')
                                </button>
                            </div>
                        </form>
                    </modal>
                    @if($match->managers()->count() > 1)
                        <form method="post" action="{{ action('Match\MatchUsersController@stopManaging', $match) }}" class="mb-1">
                            {{ csrf_field() }}
                            {{ method_field('delete') }}
                            <button class="btn btn-warning col-12 col-md-4"><i class="fa fa-times-circle"></i> @lang('match/show.stopManaging')
                            </button>
                        </form>
                    @endif
                    <form method="post" action="{{ action('Match\MatchController@delete', $match) }}" class="mb-1">
                        {{ csrf_field() }}
                        {{ method_field('delete') }}
                        <button class="btn btn-danger col-12 col-md-4"><i class="fa fa-times-circle"></i> @lang('match/show.deleteMatch')
                        </button>
                    </form>
                @endif
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
                            <span class="list-group-item list-group-item-info"><strong>@lang('match/show.players'): </strong></span>
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
                             :center="[{{$match->lat}},{{$match->lng}}]"
                             :init-markers="[[{{$match->lat}},{{$match->lng}}]]"
                             :zoom="15">
                </leaflet-map>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if(Session::has('alert'))
        <script>
			swal({
				title: 'Success',
				text: '{{ Session::get('alert') }}',
				type: 'success',
				timer: 2000
			});
        </script>
    @elseif(count($errors) > 0)
        <script>
			swal({
				title: 'Error',
				text: '{{ $errors->first() }}',
				type: 'error',
				timer: 2000
			});
        </script>
    @endif
@endsection