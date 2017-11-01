<div class="my-1">
    <button class="btn btn-success sm-btn-block" @click="toggleModal('repeatMatch')"><i
                class="fa fa-repeat"></i> @lang('match/show.repeatMatch')</button>
    <modal v-cloak ref="repeatMatch">
        <form method="post"
              action="{{ action('Match\MatchUsersController@inviteManagers', $match) }}"
              slot="body">
            {{ csrf_field() }}
            <div class="form-group">
                <date-picker label="@lang('match/create.date'):" name="date"></date-picker>
            </div>
            <div class="form-group">

                <time-picker label="@lang('match/create.startTime'):" name="time"></time-picker>
            </div>
            <div class="form-group">
                <button class="btn btn-info btn-block"><i
                            class="fa fa-repeat"></i> @lang('match/show.repeatMatch')
                </button>
            </div>
        </form>
    </modal>
</div>