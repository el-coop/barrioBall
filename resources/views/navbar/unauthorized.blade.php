<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">{{config('app.name')}}</a>
        </div>
        <ul class="nav navbar-nav navbar-right">

            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    {{ Config::get('languages')[App::getLocale()] }}

                    <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">
                    <li>
                    @foreach (Config::get('languages') as $lang => $language)
                        @if ($lang != App::getLocale())
                            <li>
                                <a href="{{action ('LanguageController@switchLang', $lang) }}">{{$language}}</a>
                            </li>
                            @endif
                            @endforeach
                    </li>
                </ul>
            </li>
        </ul>
    </div>

</nav>

