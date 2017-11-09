
@guest
    <div class="my-1">
        <a href="{{ action("Auth\LoginController@login") }}">
            <button class="btn btn-success sm-btn-block"><i
                        class="fa fa-plus-circle"></i> @lang('match/show.login')
            </button>
        </a>
    </div>
@elseif($match->ended())
    @manager($match)
        @include('match.show.repeatGame')
    @else
        <div class="my-1">
            <a href="{{ action('Match\MatchController@showSearch') }}">
                <button class="btn btn-info sm-btn-block"><i
                            class="fa fa-search"></i> @lang('match/show.matchEnded')
                </button>
            </a>
        </div>
    @endmanager
@elseif($user->inMatch($match))
    <form method="post" action="{{ action('Match\MatchUsersController@leaveMatch', $match) }}"
          class="my-1">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button class="btn btn-warning sm-btn-block"><i
                    class="fa fa-minus-circle"></i> @lang('match/show.leaveMatch')
        </button>
    </form>
@elseif($user->sentRequest($match))
    <div class="my-1">
        <button class="btn btn-info sm-btn-block" disabled><i
                    class="fa fa-minus-circle"></i> @lang('match/show.waitingForResponse')
        </button>
    </div>
    <form method="post" action="{{ action('Match\MatchUsersController@cancelJoin', $match) }}"
          class="my-1">
        {{ csrf_field() }}
        <button class="btn btn-danger sm-btn-block" dusk="cancel-join-button"><i
                    class="fa fa-minus-circle"></i> @lang('match/show.cancelJoinRequest')
        </button>
    </form>
@elseif($match->isFull())
    <div class="my-1">
        <button class="btn btn-info sm-btn-block" disabled><i
                    class="fa fa-info-circle"></i> @lang('match/show.matchFull')
        </button>
    </div>
@elseif($user->isManager($match))
    <form method="post" action="{{ action('Match\MatchUsersController@joinMatch', $match) }}"
          class="my-1">
        {{ csrf_field() }}
        <button class="btn btn-success sm-btn-block"><i
                    class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')
        </button>
    </form>
@else
    @include('match.show.sendJoinRequest')
@endguest