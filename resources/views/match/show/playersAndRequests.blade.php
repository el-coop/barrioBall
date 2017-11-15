<div class="list-group">
    <span class="list-group-item list-group-item-info">
        <strong>@lang('match/show.players'): </strong>
    </span>
    @include('match.show.playersList')
</div>
@can('manage',$match)
    <div class="list-group mt-2">
        <div class="list-group-item list-group-item-info"><strong>@lang('match/show.joinRequests'):</strong></div>
        @include('match.show.requestsList')
    </div>
@endcan
