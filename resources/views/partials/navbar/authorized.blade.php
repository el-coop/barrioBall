@component('partials.navbar.components.navbar')
    @slot('navbarCenter')
        <li class="nav-item">
            <a class="nav-link {{ Request::is('home') ? 'active' : '' }}"
               href="{{ action('HomeController@index') }}">@lang('global/dashboard.pageTitle')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('matches') ? 'active' : '' }}"
               href="{{ action('Match\MatchController@showCreate') }}">@lang('navbar.createLink')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('search') ? 'active' : '' }}"
               href="{{ action('Match\MatchController@showSearch') }}">@lang('navbar.searchLink')</a>
        </li>
        @can('admin', \App\Models\Admin::class)
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ Request::is('admin/*') ? 'active' : '' }}"
                   data-toggle="dropdown">@lang('navbar.adminTitle')</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item {{ Request::is('admin') ? 'active' : '' }}"
                       href="{{ action( 'Admin\PageController@index') }}">@lang('navbar.adminOverview')</a>
                    <a class="dropdown-item {{ Request::is('admin/errors') ? 'active' : '' }}"
                       href="{{ action( 'Admin\ErrorController@show') }}">@lang('navbar.errorsLink')</a>
                </div>
            </li>
        @endif
    @endslot
    @slot('navbarRight')
        <li class="nav-item {{ Request::is('user/conversations') ? 'active' : '' }}">
            <a class="nav-link" href="{{ action('User\ConversationsController@showConversations') }}">
                <span class="position-relative">
                    <i class="fa" :class="{'fa-comment': messagesCount > 0, 'fa-comment-o' : messagesCount == 0}">
                    </i>
                    <span class="badge badge-info small-badge" v-text="messagesCount" v-if="messagesCount > 0"></span>
                </span>
                <span class="d-md-hidden">| @lang('navbar.conversationsLink')</span>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user" aria-hidden="true"></i> |
                {{ $user->username }}
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="{{ action('User\UserController@show') }}">@lang('navbar.profileLink')</a>
                <a class="dropdown-item" href="{{ action('HomeController@showContactUs') }}">
                    @lang('navbar.contactLink')
                </a>
                <div class="dropdown-divider"></div>
                <form method="post" action="{{ action("Auth\LoginController@logout") }}">
                    {{ csrf_field() }}
                    <button class="dropdown-item">@lang('navbar.logoutLink')</button>
                </form>
            </div>
        </li>
    @endslot
@endcomponent
