<li class="nav-item dropdown user_dropdown">
    <a href="#" data-toggle="dropdown">
        <img src="{!! user()->getAvatarPath(50, 50) !!}" alt="{{ user()->name }}">
        <span class="username">{{ user()->name }}</span>
    </a>

    <div class="dropdown-menu user_menu">
        <a class="dropdown-item" href="{!! route('user_profile', user()) !!}">
            <i class="fa fa-user"></i>
            {{ trans('common.your profile') }}
        </a>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="/conversations">
            <i class="fa fa-envelope"></i>
            {{ trans('common.conversations') }}
        </a>

        <a class="dropdown-item" href="{!! action('SettingsController@showSettings') !!}">
            <i class="fa fa-cogs"></i>
            {{ trans('common.settings') }}
        </a>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item action_link" onclick="$('.logout_form').submit()">
            <i class="fa fa-sign-out"></i>
            {{ trans('common.logout') }}
        </a>

        {!! Form::open(['action' => 'AuthController@logout', 'class' => 'logout_form']) !!}
        {!! Form::close() !!}
    </div>
</li>
