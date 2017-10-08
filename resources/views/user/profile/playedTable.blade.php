@component('partials.components.panel')
    @slot('title')
        <h4>@lang('profile/page.playedMatches')</h4>
    @endslot
    <datatable
            url="{{ action('UserController@getMatches')}}"
            :fields="playedMatchesFields"
            :inline-forms="false"
            :per-page-options="[5,10]"
            class="mt-3">
    </datatable>
@endcomponent