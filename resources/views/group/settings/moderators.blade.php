<div class="tab-pane fade" id="moderators">
    @if (Auth::check() && Auth::user()->isAdmin($group))
        {{ html()->form(action: action('Group\ModeratorController@addModerator'))->class(['form-horizontal', 'mt-5'])->open() }}

        <input type="hidden" name="groupname" value="{{ $group->urlname }}">

        @include('global.form.input', ['type' => 'text', 'name' => 'username', 'class' => 'user_typeahead', 'label' => 'Nazwa użytkownika'])

        <div class="form-group">
            <div class="col-lg-6 offset-lg-3">
                <div class="checkbox">
                    <label>
                        {{ html()->checkbox('admin') }} <span class="has_tooltip" data-toggle="tooltip" title="Pozwala edytować ustawienia i listę moderatorów">Admin</span>
                    </label>
                </div>
            </div>
        </div>

        @include('global.form.submit', ['label' => 'Dodaj moderatora'])

        {{ html()->form()->close() }}
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
