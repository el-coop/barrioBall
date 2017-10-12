@component('partials.components.panel')
    @slot('title')
        <h4>@lang('admin/errors.phpErrors')</h4>
    @endslot
    <datatable
            url="{{ action('Admin\ErrorController@getPhpErrors')}}"
            :fields="phpErrorFields"
            detail-row="php-detail-row"
            ref="phpTable"
            delete-class="btn-success"
            delete-icon="fa-check"
            @delete="onDelete"
            class="mt-3">
    </datatable>
@endcomponent