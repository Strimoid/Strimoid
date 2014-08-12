<div class="list-group conversations_list">
@foreach ($conversations as $conv)
<?php $user = $conv->getUser(); ?>
<a href="{{ route('conversation', $conv->_id) }}" class="list-group-item @if (isset($conversation) && $conv->_id == $conversation->_id)active @endif">
    <img src="{{ $user->getAvatarPath() }}">

    <div class="media-body">
        <h4 class="list-group-item-heading">{{ $user->name }}</h4>
        <p class="list-group-item-text">
            {{{ Str::limit(strip_tags($conv->lastMessage->text), 50) }}}
        </p>

    </div>
</a>
@endforeach
</div>
