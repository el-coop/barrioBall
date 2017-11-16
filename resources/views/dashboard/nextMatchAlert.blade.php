<div class="alert alert-success">
    @if($nextMatch)
        <h4 class="alert-heading">
            @lang('global/dashboard.upcomingMatch',[
            'name' => $nextMatch->name,
            'url' => $nextMatch->url
            ])
        </h4>
        <p>@lang('global/dashboard.matchTime',[ 'time' => $nextMatch->date_time->diffForHumans()])</p>
    @else
        <h4 class="alert-heading">@lang('global/dashboard.noUpcomingMatches')</h4>
        <a href="{{ action('Match\MatchController@showSearch') }}" class="alert-link">@lang('global/dashboard.find')</a>
    @endif
</div>
