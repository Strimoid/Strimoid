<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body">
                {{--
                <a class="btn btn-secondary btn-block" href="">Zaloguj przez Facebook</a>

                <hr>
                --}}

                {!! Form::open(['action' => 'AuthController@login', 'class' => 'navbar-form']) !!}
                <input type="text" name="username" placeholder="{{ ucfirst(trans('auth.username')) }}" class="form-control" style="margin-bottom: 10px" autofocus>
                <input type="password" name="password" placeholder="{{ ucfirst(trans('auth.password')) }}" class="form-control" style="margin-bottom: 10px">

                <div class="m-b-1">
                    <label class="c-input c-checkbox">
                        <input type="checkbox" name="remember" value="true">
                        <span class="c-indicator"></span>
                        {{ ucfirst(trans('auth.remember')) }}
                    </label>

                    <a href="{{ route('auth.remind') }}" class="pull-right">
                        {{ ucfirst(trans('auth.forgot_password')) }}
                    </a>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    {{ ucfirst(trans('auth.sign_in')) }}
                </button>
                {!! Form::close() !!}

                <hr>

                {{ ucfirst(trans('auth.no_account_yet')) }}
                <a href="{{ route('auth.register') }}">
                    {{ ucfirst(trans('auth.create_it')) }}
                </a>
            </div>
        </div>
    </div>
</div>
