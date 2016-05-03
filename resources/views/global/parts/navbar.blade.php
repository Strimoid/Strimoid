<div class="navbar navbar-inverse navbar-{{ $navbarClass }}">
    <div class="container">
        <button class="navbar-toggler hidden-sm-up pull-xs-right" type="button"
                data-toggle="collapse" data-target="#collapsenav">
            &#9776;
        </button>

        <a class="navbar-logo pull-left" href="/">
            <img src="/static/img/logo64.png" alt="Strimoid">
        </a>

        <div class="collapse navbar-toggleable-sm pull-xs-left pull-md-none" id="collapsenav">
            <ul class="nav navbar-nav pull-left">
                @include('global.parts.tabs')
            </ul>

            <ul class="nav navbar-nav pull-right">
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
</div>
