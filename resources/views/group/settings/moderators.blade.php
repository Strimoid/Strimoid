<div class="tab-pane fade" id="moderators">
    @if (Auth::check() && Auth::user()->isAdmin($group))
        {!! Form::open(['action' => 'Group\ModeratorController@addModerator', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

        <input type="hidden" name="groupname" value="{!! $group->urlname !!}">

        @include('global.form.input', ['type' => 'text', 'name' => 'username', 'class' => 'user_typeahead', 'label' => 'Nazwa użytkownika'])

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-6">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('admin') !!} <span class="has_tooltip" data-toggle="tooltip" title="Pozwala edytować ustawienia i listę moderatorów">Admin</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-6">
                <button type="submit" class="btn btn-primary pull-right">Dodaj moderatora</button>
            </div>
        </div>

        {!! Form::close() !!}
    @endif

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Nazwa użytkownika</th>
            <th>Dodano</th>
            <th>Akcja</th>
        </tr>
        </thead>
        <tbody>

        <?php $x = 0; ?>

        @foreach ($moderators as $moderator)
            <?php $x++; ?>
            <tr>
                <td>{!! $x !!}</td>
                <td><a href="{!! route('user_profile', $moderator->user->name) !!}">{!! $moderator->user->name !!}</a></td>
                <td>{!! $moderator->created_at->diffForHumans() !!}</td>
                <td><button type="button" class="btn btn-xs btn-secondary">Usuń</button></td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>
