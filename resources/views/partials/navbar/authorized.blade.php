<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">{{config('app.name')}}</a>

    <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('matches') ? 'active' : '' }}" href="{{ action('Match\MatchController@showCreate') }}">@lang('navbar.createLink')</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('search') ? 'active' : '' }}" href="{{ action('Match\MatchController@showSearch') }}">@lang('navbar.searchLink')</a>
            </li>
            @if($user->isAdmin())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('admin/*') ? 'active' : '' }}" data-toggle="dropdown">@lang('navbar.adminTitle')</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item {{ Request::is('admin/errors') ? 'active' : '' }}" href="{{ action( 'Admin\ErrorController@show') }}">@lang('navbar.errorsLink')</a>
                    </div>
                </li>
            @endif
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-user" aria-hidden="true"></i> |
                    {{ $user->username }}
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item">@lang('navbar.profileLink')</a>
                    <div class="dropdown-divider"></div>
                    <form method="post" action="{{ action("Auth\LoginController@logout") }}">
                        {{ csrf_field() }}
                        <button class="dropdown-item">@lang('navbar.logoutLink')</button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>

