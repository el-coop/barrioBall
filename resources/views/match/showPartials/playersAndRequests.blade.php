<div class="list-group">
    <span class="list-group-item list-group-item-info">
        <strong>@lang('match/show.players'): </strong>
    </span>
    @foreach($match->registeredPlayers as $player)
        <a class="list-group-item d-flex justify-content-between align-items-center">
            {{$player->username}}
            @if($user && $user->isManager($match) && ! $player->isManager($match))
                <button class="btn btn-danger"
                        @click="userId={{ $player->id }};user='{{ $player->username }}';toggleModal('removeUser')">
                    <i class="fa fa-times"></i>
                </button>
            @endif
        </a>
    @endforeach
</div>
@if($user && $user->isManager($match))
    <div class="list-group mt-2">
        <div class="list-group-item list-group-item-info"><strong>@lang('match/show.joinRequests'):</strong></div>
        @foreach($match->joinRequests as $request)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <a>{{$request->username}}</a>
                <div class="btn-group">
                    <button class="btn btn-success"
                            @click="userId={{ $request->id }};user='{{ $request->username }}';toggleModal('acceptRequest')">
                        <i class="fa fa-check"></i>
                    </button>
                    <button class="btn btn-danger"
                            @click="userId={{ $request->id }};user='{{ $request->username }}';toggleModal('rejectRequest')">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        @endforeach
        <modal v-cloak ref="acceptRequest">
            <form method="post" slot="body" action="{{ action('Match\MatchUsersController@acceptJoin', $match) }}">
                {{ csrf_field() }}
                <input type="hidden" :value="userId" name="user">
                <div class="form-group">
                    <label for="message">
                        @lang('global.message') @{{ user }}
                        <span class="text-muted">(500 chars max)</span>:
                    </label>
                    <textarea class="form-control" name="message" rows="6" v-model="message"
                              @keyup="limitMessage"></textarea>
                </div>
                <button class="btn btn-success btn-block">
                    <i class="fa fa-plus-circle"></i> @lang('match/show.add1') @{{ user }} @lang('match/show.add2')
                </button>
            </form>
        </modal>
        <modal v-cloak ref="rejectRequest">
            <form method="post" slot="body" action="{{ action('Match\MatchUsersController@rejectJoin', $match) }}">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <input type="hidden" :value="userId" name="user">
                <div class="form-group">
                    <label for="message">
                        @lang('global.message') @{{ user }}
                        <span class="text-muted">(500 chars max)</span>:
                    </label>
                    <textarea class="form-control" name="message" rows="6" v-model="message"
                              @keyup="limitMessage"></textarea>
                </div>
                <button class="btn btn-danger btn-block">
                    <i class="fa fa-minues-circle"></i> @lang('match/show.rejectRequest') @{{ user }}
                </button>
            </form>
        </modal>
    </div>
    <modal v-cloak ref="removeUser" id="remove-user">
        <form method="post" slot="body" action="{{ action('Match\MatchUsersController@removePlayer', $match) }}">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <input type="hidden" :value="userId" name="user">
            <div class="form-group">
                <label for="message">
                    @lang('global.message') @{{ user }}
                    <span class="text-muted">(500 chars max)</span>:
                </label>
                <textarea class="form-control" name="message" rows="6" v-model="message"
                          @keyup="limitMessage"></textarea>
            </div>
            <button class="btn btn-danger btn-block">
                <i class="fa fa-minus-circle"></i> @lang('match/removePlayer.confirm')
            </button>
        </form>
    </modal>
@endif
