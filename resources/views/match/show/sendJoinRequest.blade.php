<div class="my-1">
    <button class="btn btn-success sm-btn-block" @click="toggleModal({
                            url: '{{ action('Match\MatchUserController@joinMatch', $match) }}',
                            class: 'btn-success',
                            delete: false,
                            title: '@lang('match/show.introduceYourself')',
                            user: 0,
                            buttonText: '@lang('match/show.joinRequest')',
                            buttonIcon: 'fa-plus-circle'
                        })">
        <i class="fa fa-plus-circle"></i> @lang('match/show.joinRequest')
    </button>
</div>