<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
    @if($container ?? false)
        <div class="container">
            @endif
            <a class="navbar-brand" href="/">{{config('app.name')}}</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
                    data-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav mr-auto">
                    {{ $navbarCenter }}
                </ul>
                <ul class="navbar-nav">
                    {{ $navbarRight }}
                </ul>
            </div>
            @if($container ?? false)
        </div>
    @endif
</nav>

