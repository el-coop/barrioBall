<div class="row">
    <div class="col-12 col-md-8 mb-2">
        <div class="row">
            <div class="col-12">
                <h6>@lang('global/welcome.links')</h6>
            </div>
        </div>

        <div class="row">
            @include('welcome.footerLinks')
        </div>
    </div>
    <div class="col-12 col-md-4 mb-2">
        <div class="row">
            <div class="col-12 col-md-6">
                <h6>@lang('global/welcome.languages'):</h6>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ action('HomeController@showContactUs')  }}"
                   class="text-muted">@lang('global/welcome.helpTranslate')</a>
            </div>
        </div>
        <div class="row">
            @component('welcome.components.languageColumns',[
                'languages' => config('languages')
            ])
            @endcomponent
        </div>
    </div>
</div>