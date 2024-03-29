@extends('global.master')

@section('content')

    @if (Auth::check() && Auth::user()->isModerator($group))
        {{ html()->form(action: action('Group\BanController@addBan'))->class(['form-horizontal'])->open() }}

        <input type="hidden" name="groupname" value="{!! $group->urlname !!}">

        @include('global.form.input', ['type' => 'text', 'name' => 'username', 'class' => 'user_typeahead', 'label' => 'Nazwa użytkownika'])
        @include('global.form.input', ['type' => 'text', 'name' => 'reason', 'label' => 'Powód zbanowania'])

        <div class="form-group">
            <div class="col-lg-6 offset-lg-3">
                <div class="checkbox">
                    <label>
                        {{ html()->checkbox('everywhere') }} Zablokuj we wszystkich grupach
                    </label>
                </div>
            </div>
        </div>

        @include('global.form.submit', ['label' => 'Zbanuj'])

        {{ html()->form()->close() }}
    @endif

<table class="table">
    <thead>
    <tr>
        <th>Nazwa użytkownika</th>
        <th>Zbanowany</th>
        <th>Powód</th>

        @if (Auth::check() && Auth::user()->isModerator($group))
            <th>Akcja</th>
        @endif
    </tr>
    </thead>
    <tbody>

    @foreach ($bans as $ban)
    <tr>
        <td><a href="{!! route('user_profile', $ban->user->name) !!}">{!! $ban->user->name !!}</a></td>
        <td><time pubdate datetime="{!! $ban->created_at->format('c') !!}" title="{!! $ban->getLocalTime() !!}">{!! $ban->created_at->diffForHumans() !!}</time></td>
        <td>{{{ $ban->reason }}}</td>

        @if (Auth::check() && Auth::user()->isModerator($group))
            <td><button type="button" class="btn btn-xs btn-secondary ban_remove_btn" data-id="{!! $ban->id !!}"><span class="glyphicon glyphicon-remove"></span> Usuń</button></td>
        @endif
    </tr>
    @endforeach

    </tbody>

</table>

{!! $bans->links() !!}
@stop

@section('sidebar')
@include('group.sidebar.add_content')

@if (isset($group))
@include('group.sidebar.description', ['group' => $group])
@include('group.sidebar.stats', ['group' => $group])
@endif

@include('group.sidebar.popular_contents')
@stop
