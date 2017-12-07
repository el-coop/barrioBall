@component('partials.components.panel')
    @slot('title')
        <h4 class="mb-0">@lang('admin/overview.matches')</h4>
    @endslot
    <datatable
            url="{{ action('Admin\PageController@getMatches')}}"
            :fields="matchTableFields"
            :inline-forms="false"
            :per-page-options="[5,10]"
            class="mt-3">
    </datatable>
@endcomponent