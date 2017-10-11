@component('partials.components.panel')
    @slot('title')
        <h4>@lang('admin/errors.jsErrors')</h4>
    @endslot
    <datatable
            url="{{ action('Admin\ErrorController@getJsErrors') }}"
            :fields="jsErrorFields"
            detail-row="js-detail-row"
            ref="jsTable"
            delete-class="btn-success"
            delete-icon="fa-check"
            @delete="onDelete">
    </datatable>
@endcomponent