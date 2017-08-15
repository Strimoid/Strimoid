<nav class="navbar navbar-expand-lg navbar-inverse navbar-{{ $navbarClass }}">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/static/img/logo64.png" width="40" height="40" alt="Strm">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsenav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="collapsenav">
            <ul class="nav navbar-nav mr-auto">
                @include('global.parts.tabs')
            </ul>

            <ul class="nav navbar-nav">
                <li class="nav-item hidden-md-down">
                    <a class="toggle_night_mode">
                        <i class="fa fa-adjust"></i>
                    </a>
                </li>

                @if (Auth::check())
                    @include('global.parts.notifications')
                    @include('global.parts.user_dropdown')
                @else
                    @include('global.parts.login')
                @endif
            </ul>
        </div>
    </div>
</nav>
