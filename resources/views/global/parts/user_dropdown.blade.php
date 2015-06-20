<li class="dropdown user_dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{!! Auth::user()->getAvatarPath(50, 50) !!}">
        {!! Auth::user()->name !!} <b class="caret"></b>
    </a>

    <ul class="dropdown-menu user_menu">
        <li>
            <a href="{!! route('user_profile', user()) !!}">
                <span class="glyphicon glyphicon-user"></span> {{ trans('common.your profile') }}
            </a>
        </li>
        <li>
            <a href="/conversations">
                <span class="glyphicon glyphicon-envelope"></span> {{ trans('common.conversations') }}
            </a>
        </li>
        <li>
            <a href="{!! action('SettingsController@showSettings') !!}">
                <span class="glyphicon glyphicon-wrench"></span> {{ trans('common.settings') }}
            </a>
        </li>

        <li class="divider"></li>

        <li>
            <a class="action_link" onclick="$('.logout_form').submit()">
                <span class="glyphicon glyphicon-log-out"></span> {{ trans('common.logout') }}
            </a>
            {!! Form::open(['action' => 'AuthController@logout', 'class' => 'logout_form']) !!}
            {!! Form::close() !!}
        </li>
    </ul>
</li>
