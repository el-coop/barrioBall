<div class="list-group">
    <span class="list-group-item list-group-item-info"><strong>@lang('match/show.players'): </strong></span>
    @foreach($match->registeredPlayers as $player)
        <a class="list-group-item">{{$player->username}}</a>
    @endforeach
</div>
@if($user && $user->isManager($match))
    <div class="list-group mt-2">
        <div class="list-group-item list-group-item-info"><strong>Join Requests:</strong></div>
        @foreach($match->joinRequests as $request)
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between align-items-center">
                <a>{{$request->username}}</a>
                    <div class="btn-group">
                        <button class="btn btn-success" @click="userId={{ $request->id }};user='{{ $request->username }}';toggleModal('acceptRequest')">
                            <i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-danger">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
        <modal v-cloak ref="acceptRequest">
            <form method="post" slot="body" action="{{ action('Match\MatchUsersController@acceptJoin', $match) }}">
                {{ csrf_field() }}
                <input type="hidden" :value="userId" name="user">
                <div class="form-group">
                    <label for="message">Message @{{ user }}<span
                                class="text-muted">(500 chars max)</span>:</label>
                    <textarea class="form-control" name="message" rows="6"
                              v-model="message" @keyup="limitMessage"></textarea>
                </div>
                <button class="btn btn-success btn-block"><i
                            class="fa fa-plus-circle"></i> Add @{{ user }} to match
                </button>
            </form>
        </modal>

    </div>
@endif
