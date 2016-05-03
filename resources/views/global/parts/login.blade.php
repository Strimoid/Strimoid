<li class="nav-item dropdown">
    <a href="#" onclick="$('#login-modal').modal('show'); return false">
        {{ trans('auth.logging in') }} <b class="caret"></b>
    </a>
</li>
<li class="nav-item hidden-md-down">
    <a href="{{ route('auth.register') }}">
        {{ trans('auth.registration') }}
    </a>
</li>
