@guest
    <div class="my-1">
        <a href="{{ action("Auth\LoginController@login") }}">
            <button class="btn btn-success sm-btn-block"><i
                        class="fa fa-plus-circle"></i> @lang('match/show.login')
            </button>
        </a>
    </div>
    @elseif($match->ended())
        @can('manage',$match)
            @include('match.show.repeatGame')
        @else
            <div class="my-1">
                <a href="{{ action('Match\MatchController@showSearch') }}">
                    <button class="btn btn-info sm-btn-block"><i
                                class="fa fa-search"></i> @lang('match/show.matchEnded')
                    </button>
                </a>
            </div>
        @endcan
    @elseif($match->hasPlayer($user))
        <form method="post" action="{{ action('Match\MatchUserController@leaveMatch', $match) }}"
              class="my-1">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn-warning sm-btn-block"><i
                        class="fa fa-minus-circle"></i> @lang('match/show.leaveMatch')
            </button>
        </form>
    @elseif($match->hasJoinRequest($user))
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                @lang('match/show.waitingForResponse')
            </button>
            <div class="dropdown-menu">
                <form method="post" action="{{ action('Match\MatchUserController@cancelJoinRequest', $match) }}">
                    {{ csrf_field() }}
                    <button class="dropdown-item">
                        @lang('match/show.cancelJoinRequest')
                    </button>
                </form>
            </div>
        </div>
    @elseif($match->isFull())
        <div class="my-1">
            <button class="btn btn-info sm-btn-block" disabled><i
                        class="fa fa-info-circle"></i> @lang('match/show.matchFull')
            </button>
        </div>
    @elseif($user->can('manage',$match))
        <form method="post" action="{{ action('Match\MatchUserController@joinMatch', $match) }}"
              class="my-1">
            {{ csrf_field() }}
            <button class="btn btn-success sm-btn-block"><i
                        class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')
            </button>
        </form>
    @else
        @include('match.show.sendJoinRequest')
@endguest