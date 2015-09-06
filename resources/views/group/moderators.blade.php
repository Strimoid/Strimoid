@extends('global.master')

@section('content')

@if (Auth::check() && Auth::user()->isAdmin($group))
{!! Form::open(array('action' => 'GroupController@addModerator', 'class' => 'form-horizontal')) !!}

<input type="hidden" name="groupname" value="{!! $group->urlname !!}">

@include('global.form.input', array('type' => 'text', 'name' => 'username', 'class' => 'user_typeahead', 'label' => 'Nazwa użytkownika'))

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
            <td><button type="button" class="btn btn-sm btn-secondary moderator_remove_btn" data-id="{!! $moderator->_id !!}"><span class="glyphicon glyphicon-remove"></span> Usuń</button></td>
        @endif
    </tr>
    @endforeach

    </tbody>

</table>

{!! with(new BootstrapPresenter($moderators))->render() !!}
@stop

@section('sidebar')
@include('group.sidebar.add_content')

@if (isset($group))
@include('group.sidebar.description', array('group' => $group))
@include('group.sidebar.stats', array('group' => $group))
@endif

@include('group.sidebar.popular_contents')
@stop
