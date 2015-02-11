@extends('global.master')

@section('content')

@if (!$messages)
{!! Form::open(array('action' => 'ConversationController@createConversation', 'class' => 'form-horizontal')) !!}

@include('global.form.input', ['type' => 'text', 'name' => 'username', 'label' => 'Nazwa użytkownika'])
@include('global.form.input', ['type' => 'textarea', 'name' => 'text', 'label' => 'Wiadomość'])

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-6">
        <button type="submit" class="btn btn-primary">Wyślij</button>
    </div>
</div>
{!! Form::close() !!}
@else

{!! with(new BootstrapPresenter($messages))->render() !!}

<div class="conversation_messages">

@foreach (array_reverse($messages->all()) as $message)
<div class="panel-default entry" data-id="{!! $message->_id !!}">
    <a name="{!! $message->_id !!}"></a>

    <div class="entry_avatar">
        <img src="{!! $message->user->getAvatarPath() !!}" alt="{!! $message->user->name !!}">
    </div>

    <div class="panel-heading entry_header">
        <a href="{!! route('user_profile', $message->user->name) !!}" class="entry_author">{!! $message->user->getColoredName() !!}</a>

        <span class="pull-right">
            <span class="glyphicon glyphicon-time"></span> <time pubdate datetime="{!! $message->created_at->format('c') !!}" title="{!! $message->getLocalTime() !!}">{!! $message->created_at->diffForHumans() !!}</time>
        </span>
    </div>

    <div class="entry_text md">
        {!! $message->text !!}
    </div>
</div>
@endforeach

</div>

@endif

@if (isset($conversation))
{!! Form::open(array('action' => array('ConversationController@sendMessage'), 'class' => 'form entry_add_form enter_send')) !!}
<input type="hidden" name="id" value="{!! $conversation->_id !!}">

<div class="panel-default entry">
    <div class="entry_avatar">
        <img src="{!! Auth::user()->getAvatarPath() !!}">
    </div>

    <div class="entry_text">
        <div class="form-group @if ($errors->has('text')) has-error @endif">
            {!! Form::textarea('text', null, ['class' => 'form-control', 'placeholder' => 'Treść wiadomości...', 'rows' => 2]) !!}

            @if($errors->has('text'))
            <p class="help-block">{!! $errors->first('text') !!}</p>
            @endif
        </div>

        <div class="pull-right">
            <button type="submit" class="btn btn-primary">Wyślij</button>
        </div>

        <div class="clearfix"></div>
    </div>
</div>
{!! Form::close() !!}
@endif

@stop

@section('sidebar')
    <div class="well">
        <a href="{!! action('ConversationController@showCreateForm', ['user' => null]) !!}" class="btn btn-default">Rozpocznij nową konwersację</a>
    </div>

    @include('conversations.list')
@stop