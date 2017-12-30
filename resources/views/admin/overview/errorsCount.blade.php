<div class="alert alert-danger">
    <h4 class="alert-heading">@lang('admin/overview.errorsCount',[
        'errors' => $errorsCount,
        'url' => action('Admin\ErrorController@show')
    ])</h4>
    <p>@lang('admin/overview.newErrorsCount',[
        'errors' => $newErrors
    ])</p>
    <hr>
    <p class="mb-0">@lang('admin/overview.phpErrorsCount',[
        'errors' => $phpErrors
    ])</p>
    <p class="mb-0">@lang('admin/overview.jsErrorsCount',[
        'errors' => $jsErrors
    ])</p>
</div>
