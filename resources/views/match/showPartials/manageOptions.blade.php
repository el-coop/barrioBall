@if($user && $user->isManager($match))
    <span>
        <button class="col-12 col-md-6 col-lg-4 btn btn-info mb-1" @click="toggleModal('inviteManagers')"><i
                    class="fa fa-plus-circle"></i> @lang('match/show.inviteManagers')</button>
        <modal v-cloak ref="inviteManagers">
            <form method="post"
                  action="{{ action('Match\MatchUsersController@inviteManagers', $match) }}"
                  slot="body">
                {{ csrf_field() }}
                <div class="form-group">
                    <multi-select name="invite_managers"
                                  label="name"
                                  action="{{ action('Match\MatchUsersController@searchUsers', $match) }}"
                                  placeholder="@lang('match/show.invitePlaceholder')"
                    ></multi-select>
                </div>
                <div class="form-group">
                    <button class="btn btn-info btn-block"><i
                                class="fa fa-plus-circle"></i> @lang('match/show.inviteManagers')
                    </button>
                </div>
            </form>
        </modal>
    </span>
    @if($match->managers()->count() > 1)
        <form method="post" action="{{ action('Match\MatchUsersController@stopManaging', $match) }}"
              class="mb-1">
            {{ csrf_field() }}
            {{ method_field('delete') }}
            <button class="btn btn-warning col-12 col-md-6 col-lg-4"><i
                        class="fa fa-times-circle"></i> @lang('match/show.stopManaging')
            </button>
        </form>
    @endif
    <form method="post" action="{{ action('Match\MatchController@delete', $match) }}" class="mb-1">
        {{ csrf_field() }}
        {{ method_field('delete') }}
        <swal-submit class="btn btn-danger col-12 col-md-6 col-lg-4"
                     title="@lang('match/show.areYouSure')"
                     confirm-text="@lang('match/show.deleteMatch')"
                     cancel-text="No"
        ><i class="fa fa-times-circle"></i> @lang('match/show.deleteMatch')
        </swal-submit>
    </form>
@endif