@component('partials.components.panel')
    @slot('title')
        <h4 class="mb-0">@lang('profile/page.playedMatches')</h4>
    @endslot
    <datatable
            url="{{ action('User\UserController@getMatches')}}"
            :fields="playedMatchesFields"
            :inline-forms="false"
            :sort-order="playedSortOrder"
            :per-page-options="[5,10]"
            class="mt-3">
    </datatable>
@endcomponent