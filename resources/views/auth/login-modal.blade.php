<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                {{--
                <a class="btn btn-secondary btn-block" href="">Zaloguj przez Facebook</a>

                <hr>
                --}}

                {!! Form::open(['action' => 'AuthController@login', 'class' => 'navbar-form']) !!}
                <input type="text" name="username" placeholder="Login" class="form-control" style="margin-bottom: 10px">
                <input type="password" name="password" placeholder="Hasło" class="form-control" style="margin-bottom: 10px">

                <div class="m-b-1">
                    <label class="c-input c-checkbox">
                        <input type="checkbox" name="remember" value="true">
                        <span class="c-indicator"></span>
                        Zapamiętaj mnie
                    </label>

                    <a href="{{ route('auth.remind') }}" class="pull-right">Zapomniałeś hasła?</a>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Zaloguj</button>
                {!! Form::close() !!}

                <hr>

                Nie masz jeszcze konta? <a href="{{ route('auth.register') }}">Załóż je!</a>
            </div>
        </div>
    </div>
</div>
