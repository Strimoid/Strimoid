@extends('global.master')

@section('content')

@if (Auth::check() && Auth::user()->isAdmin($group))
    {{ html()->form(action: action('Group\ModeratorController@addModerator'))->class(['form-horizontal'])->open() }}

    <input type="hidden" name="groupname" value="{!! $group->urlname !!}">

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
        <th>Nazwa użytkownika</th>
        <th>Funkcja</th>
        <th>Dodany</th>

        @if (Auth::check() && Auth::user()->isAdmin($group))
            <th>Akcja</th>
        @endif
    </tr>
    </thead>
    <tbody>

    @foreach ($moderators as $moderator)
    <tr>
        <td><a href="{!! route('user_profile', $moderator->user->name) !!}">{!! $moderator->user->name !!}</a></td>
        <td>@if($moderator->type == 'admin') Administrator @else Moderator @endif</td>
        <td><time pubdate datetime="{!! $moderator->created_at->format('c') !!}" title="{!! $moderator->getLocalTime() !!}">{!! $moderator->created_at->diffForHumans() !!}</time></td>

        @if (Auth::check() && Auth::user()->isAdmin($group))
            <td><button type="button" class="btn btn-sm btn-secondary moderator_remove_btn" data-id="{!! $moderator->hashId() !!}"><span class="glyphicon glyphicon-remove"></span> Usuń</button></td>
        @endif
    </tr>
    @endforeach

    </tbody>

</table>

{!! $moderators->links() !!}
@stop

@section('sidebar')
@include('group.sidebar.add_content')

@if (isset($group))
@include('group.sidebar.description', ['group' => $group])
@include('group.sidebar.stats', ['group' => $group])
@endif

@include('group.sidebar.popular_contents')
@stop
