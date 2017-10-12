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