<div class="mt-1 mb-1">
    <button class="btn btn-success sm-btn-block" @click="toggleModal('joinRequest')"><i
                class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')</button>
    <modal v-cloak ref="joinRequest">
        <form method="post"
              action="{{ action('Match\MatchUsersController@joinMatch', $match) }}"
              class="mt-1 mb-1" slot="body">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="message">@lang('match/show.introduceYourself')<span
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