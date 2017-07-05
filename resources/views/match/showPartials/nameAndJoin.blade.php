<h2>
    {{ $match->name }}
</h2>
@lang('match/show.managedBy'):
@foreach($match->managers as $manager)
    <a href="#">{{ $manager->username }}</a>@if (!$loop->last), @endif
@endforeach
@if(! $user)
    <div class="mb-1">
        <a href="{{ action("Auth\LoginController@login") }}">
            <button class="btn btn-success sm-btn-block"><i
                        class="fa fa-plus-circle"></i> @lang('match/show.login')
            </button>
        </a>
    </div>
@elseif($user->inMatch($match))
    <form method="post" action="{{ action('Match\MatchUsersController@leaveMatch', $match) }}"
          class="mb-1">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <button class="btn btn-warning sm-btn-block"><i
                    class="fa fa-minus-circle"></i> @lang('match/show.leaveMatch')
        </button>
    </form>
@elseif($user->isManager($match))
    <form method="post" action="{{ action('Match\MatchUsersController@joinMatch', $match) }}"
          class="mt-1 mb-1">
        {{ csrf_field() }}
        <button class="btn btn-success sm-btn-block"><i
                    class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')
        </button>
    </form>
@elseif($user->sentRequest($match))
    <div class="bm-1">
        <button class="btn btn-info sm-btn-block" disabled><i
                    class="fa fa-minus-circle"></i> Waiting for response
        </button>
    </div>
@else
    <div class="mt-1 mb-1">
        <button class="btn btn-success sm-btn-block" @click="toggleModal('joinRequest')"><i
                    class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')</button>
        <modal v-cloak ref="joinRequest">
            <form method="post"
                  action="{{ action('Match\MatchUsersController@joinMatch', $match) }}"
                  class="mt-1 mb-1" slot="body">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="message">Introduce yourself to the managers <span
                                class="text-muted">(500 chars max)</span>:</label>
                    <textarea class="form-control" name="message" rows="6"
                              v-model="message" @keyup="limitMessage"></textarea>
                </div>
                <button class="btn btn-success btn-block"><i
                            class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')
                </button>
            </form>
        </modal>
    </div>
@endif