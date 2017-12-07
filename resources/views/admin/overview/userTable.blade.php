@component('partials.components.panel')
    @slot('title')
        <h4 class="mb-0">@lang('admin/overview.users')</h4>
    @endslot
    <datatable
            url="{{ action('Admin\PageController@getUsers')}}"
            :fields="userTableFields"
            :inline-forms="false"
            :sort-order="playedSortOrder"
            :per-page-options="[5,10]"
            class="mt-3">
    </datatable>
@endcomponent