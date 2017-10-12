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