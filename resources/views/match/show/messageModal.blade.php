<modal v-cloak ref="modal" id="listsModal">
    <form method="post" slot="body" :action="modal.url">
        {{ csrf_field() }}
        <span v-if="modal.delete">
            {{ method_field('delete') }}
        </span>
        <input type="hidden" :value="modal.user" name="user">
        <div class="form-group">
            <label for="message">
                @{{ modal.title }}:
                <span class="text-muted">(@lang('match/show.charLimit'))</span>:
            </label>
            <textarea class="form-control" name="message" rows="6" v-model="message"
                      @keyup="limitMessage"></textarea>
        </div>
        <button class="btn btn-block" :class="modal.class">
            <i class="fa" :class="modal.buttonIcon"></i> @{{ modal.buttonText }}
        </button>
    </form>
</modal>