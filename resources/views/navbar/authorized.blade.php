<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">{{config('app.name')}}</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    {{ config('languages')[App::getLocale()] }}

                    <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">
                    @foreach (config('languages') as $lang => $language)
                        @if ($lang != App::getLocale())
                            <li>
                                <a href="{{action ('LanguageController@switchLang', $lang) }}">{{$language}}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>

</nav>

