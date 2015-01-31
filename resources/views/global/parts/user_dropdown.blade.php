<li class="dropdown user_dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img src="{!! Auth::user()->getAvatarPath(50, 50) !!}">
        {!! Auth::user()->name !!} <b class="caret"></b>
    </a>

    <ul class="dropdown-menu user_menu">
        <li><a href="{!! route('user_profile', Auth::user()->name) !!}"><span class="glyphicon glyphicon-user"></span> twój profil</a></li>
        <li><a href="/conversations"><span class="glyphicon glyphicon-envelope"></span> konwersacje</a></li>
        <li><a href="{!! action('UserController@showSettings') !!}"><span class="glyphicon glyphicon-wrench"></span> ustawienia</a></li>

        <li class="divider"></li>

        <li>
            <a class="action_link" onclick="$('.logout_form').submit()">
                <span class="glyphicon glyphicon-log-out"></span> wyloguj
            </a>
            {!! Form::open(array('action' => 'UserController@logout', 'class' => 'logout_form')) !!}
            {!! Form::close() !!}
        </li>
    </ul>
</li>
