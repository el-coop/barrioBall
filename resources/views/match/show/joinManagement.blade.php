<h3 class="text-center mt-1">@lang('match/show.acceptManageInvitation')?</h3>
<div class="btn-group mb-1 d-flex justify-content-center">
    <form class="btn-group" method="post" action="{{ action('Match\MatchUserController@joinAsManager', $match) }}">
        <button class="btn btn-outline-success">@lang('match/show.accept')</button>
        {{csrf_field()}}
    </form>
    <form class="btn-group" method="post" action="{{ action('Match\MatchUserController@rejectManageInvitation', $match) }}">
        {{csrf_field()}}
        {{method_field('delete')}}
        <button class="btn btn-outline-danger">@lang('match/show.reject')</button>
    </form>
</div>