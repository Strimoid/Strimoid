<nav class="navbar navbar-toggleable-md navbar-inverse navbar-{{ $navbarClass }}">
    <div class="container">
        <button class="navbar-toggler navbar-toggler-right" type="button"
                data-toggle="collapse" data-target="#collapsenav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand" href="/">
            <img src="/static/img/logo64.png" width="40" height="40" alt="Strm">
        </a>

        <div class="collapse navbar-collapse navbar-toggleable-sm" id="collapsenav">
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
