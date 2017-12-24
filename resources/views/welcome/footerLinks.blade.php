<div class="col-12 col-md-3">
    <ul class="list-unstyled mb-0">
        <li><a href="{{ action('HomeController@about') }}" class="text-muted">@lang('global/welcome.about')</a></li>
        <li><a href="{{ url('https://github.com/el-coop/barrioBall') }}"
               class="text-muted">@lang('global/welcome.code')</a></li>
        <li><a href="{{ action('HomeController@showContactUs') }}"
               class="text-muted">@lang('navbar.contactLink')</a></li>
    </ul>
</div>
<div class="col-12 col-md-3">
    <ul class="list-unstyled mb-0">
        <li><a href="#" class="text-muted">@lang('global/welcome.faq')</a></li>
        <li><a href="{{ action('HomeController@showContactUs', ['query' => 'contribute']) }}" class="text-muted">@lang('global/welcome.contribute')</a></li>
    </ul>
</div>

<div class="col-12 col-md-3">
    <ul class="list-unstyled mb-0">
        <li><a href="{{ url('https://github.com/el-coop/barrioBall/issues') }}"
               class="text-muted">@lang('global/welcome.issues')</a></li>
        <li><a href="{{ action('HomeController@tech') }}" class="text-muted">@lang('global/welcome.tech')</a></li>
    </ul>
</div>