@component('partials.components.panel')
    @slot('title')
        <h4>@lang('profile/page.mangedMatches')</h4>
    @endslot
    <datatable
            url="{{ action('UserController@getMatches')}}"
            :fields="managedMatchesFields"
            :inline-forms="false"
            :per-page-options="[5,10]"
            :extra-params="{
                managed: true
            }"
            class="mt-3">
    </datatable>
@endcomponent
