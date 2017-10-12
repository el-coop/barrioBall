@foreach($match->registeredPlayers as $player)
    <div class="list-group-item d-flex justify-content-between align-items-center">
        <a>{{$player->username}}</a>
        @if($user && $user->isManager($match) && ! $player->isManager($match))
            <button class="btn btn-danger"
                    @click="toggleModal({
                            url: '{{ action('Match\MatchUsersController@removePlayer', $match) }}',
                            class: 'btn-danger',
                            delete: true,
                            user: {{ $player->id }},
                            buttonText: '@lang('match/removePlayer.confirm',['user' => $player->username])'
                        })">
                <i class="fa fa-times"></i>
            </button>
        @endif
    </div>
@endforeach