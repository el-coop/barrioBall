<div class="list-group">
    <span class="list-group-item list-group-item-info">
        <strong>@lang('match/show.players'): </strong>
    </span>
    @include('match.show.playersList')
</div>
@manager($match)
<div class="list-group mt-2">
    <div class="list-group-item list-group-item-info"><strong>@lang('match/show.joinRequests'):</strong></div>
    @include('match.show.requestsList')
    <modal v-cloak ref="modal" id="listsModal">
        <form method="post" slot="body" :action="modal.url">
            {{ csrf_field() }}
            <span v-if="modal.delete">
                {{ method_field('delete') }}
            </span>
            <input type="hidden" :value="modal.user" name="user">
            <div class="form-group">
                <label for="message">
                    @lang('match/show.message'):
                    <span class="text-muted">(@lang('match/show.charLimit'))</span>:
                </label>
                <textarea class="form-control" name="message" rows="6" v-model="message"
                          @keyup="limitMessage"></textarea>
            </div>
            <button class="btn btn-block" :class="modal.class">
                <i class="fa fa-plus-circle"></i> @{{ modal.buttonText }}
            </button>
        </form>
    </modal>
</div>
@endmanager
