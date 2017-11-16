<div class="alert alert-info">
    @if($requestsCount)
        @if($requestsCount > 1)
            <h4 class="alert-heading">@lang('global/dashboard.pendingRequests',['number' => $requestsCount])</h4>
        @else
            <h4 class="alert-heading">@lang('global/dashboard.pendingRequest')</h4>
        @endif
        <p>@lang('global/dashboard.manageRequests')</p>
    @else
        <h4 class="alert-heading">@lang('global/dashboard.noRequests')</h4>
    @endif
</div>
