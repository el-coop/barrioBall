@component('partials.navbar.components.navbar',['container' => true])
    @slot('navbarCenter')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('search') ? 'active' : '' }}"
               href="{{ action('Match\MatchController@showSearch') }}">@lang('navbar.searchLink')</a>
        </li>
    @endslot
    @slot('navbarRight')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('login') ? 'active' : '' }}"
               href="{{ action('Auth\LoginController@showLoginForm') }}">@lang('navbar.loginLink')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('register') ? 'active' : '' }}"
               href="{{ action('Auth\RegisterController@showRegistrationForm') }}">@lang('navbar.registerLink')</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                {{ config('languages')[App::getLocale()] }}
            </a>
            <div class="dropdown-menu">
                @foreach (config('languages') as $lang => $language)
                    <a href="{{action ('LanguageController@switchLang', $lang) }}"
                       class="dropdown-item">{{$language}}</a>
                @endforeach
            </div>
        </li>
    @endslot
@endcomponent