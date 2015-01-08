@extends('...global.master')

@section('content')

@if (Auth::check() && Auth::user()->isModerator($group))
{!! Form::open(array('action' => 'GroupController@addBan', 'class' => 'form-horizontal')) !!}

<input type="hidden" name="groupname" value="{!! $group->urlname !!}">

@include('...global.form.input', array('type' => 'text', 'name' => 'username', 'class' => 'user_typeahead', 'label' => 'Nazwa użytkownika'))
@include('...global.form.input', array('type' => 'text', 'name' => 'reason', 'label' => 'Powód zbanowania'))

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('everywhere', Input::old('everywhere')) !!} Zablokuj we wszystkich grupach
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-primary pull-right">Zbanuj</button>
    </div>
</div>

{!! Form::close() !!}
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
            <td><button type="button" class="btn btn-xs btn-default ban_remove_btn" data-id="{!! $ban->_id !!}"><span class="glyphicon glyphicon-remove"></span> Usuń</button></td>
        @endif
    </tr>
    @endforeach

    </tbody>

</table>

<?php echo $bans->links(); ?>
@stop

@section('sidebar')
@include('sidebar.add_content')

@if (isset($group))
@include('sidebar.description', array('group' => $group))
@include('sidebar.stats', array('group' => $group))
@endif

@include('sidebar.popular_contents')
@stop
