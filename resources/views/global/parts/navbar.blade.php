<div class="navbar navbar-inverse navbar-{{ $navbarClass }}">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/static/img/logo.png" alt="Strimoid">
        </a>

        <ul class="nav navbar-nav">
            @include('global.parts.tabs')
        </ul>

        <ul class="nav navbar-nav pull-right">
            <li class="nav-item">
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
