<nav class="navbar navbar-expand-lg navbar-{{ $navbarClass }}">
    <div class="container">
        <a href="/">
            <img class="logo" src="/static/img/logo64.png" width="40" height="40" alt="Strm">
        </a>
        
        <ul class="nav navbar-nav navbar-tabs">
            @include('global.parts.tabs')
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item night_mode_toggler">
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
</nav>
