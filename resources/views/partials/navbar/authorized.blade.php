<nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse fixed-top">
    <div class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#">{{config('app.name')}}</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">

            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">
                        {{ config('languages')[App::getLocale()] }}
                        <b class="caret"></b>
                    </a>
                    <div class="dropdown-menu">
                        @foreach (config('languages') as $lang => $language)
                            <a href="{{action ('LanguageController@switchLang', $lang) }}"
                               class="dropdown-item">{{$language}}</a>
                        @endforeach
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>