<h2>
    {{ $match->name }}
</h2>
@lang('match/show.managedBy'):
@foreach($match->managers as $manager)
    <a href="#">{{ $manager->username }}</a>@if (!$loop->last), @endif
@endforeach
@guest
    <div class="mt-1 mb-1">
        <a href="{{ action("Auth\LoginController@login") }}">
            <button class="btn btn-success sm-btn-block"><i
                        class="fa fa-plus-circle"></i> @lang('match/show.login')
            </button>
        </a>
    </div>
@elseif($match->ended())
    <div class="mt-1 bm-1">
        <a href="{{ action('Match\MatchController@showSearch') }}"><button class="btn btn-info sm-btn-block"><i
                    class="fa fa-search"></i> @lang('match/show.matchEnded')
        </button></a>
    </div>
@elseif($user->inMatch($match))
    <form method="post" action="{{ action('Match\MatchUsersController@leaveMatch', $match) }}"
          class="mt-1 mb-1">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button class="btn btn-warning sm-btn-block"><i
                    class="fa fa-minus-circle"></i> @lang('match/show.leaveMatch')
        </button>
    </form>
@elseif($user->sentRequest($match))
    <div class="mt-1 bm-1">
        <button class="btn btn-info sm-btn-block" disabled><i
                    class="fa fa-minus-circle"></i> @lang('match/show.waitingForResponse')
        </button>
    </div>
@elseif($match->isFull())
    <div class="mt-1 bm-1">
        <button class="btn btn-info sm-btn-block" disabled><i
                    class="fa fa-info-circle"></i> @lang('match/show.matchFull')
        </button>
    </div>
@elseif($user->isManager($match))
    <form method="post" action="{{ action('Match\MatchUsersController@joinMatch', $match) }}"
          class="mt-1 mb-1">
        {{ csrf_field() }}
        <button class="btn btn-success sm-btn-block"><i
                    class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')
        </button>
    </form>
@else
    @include('match.show.sendJoinRequest')
@endguest