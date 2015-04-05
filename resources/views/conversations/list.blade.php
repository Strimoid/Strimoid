<div class="list-group conversations_list">
@foreach ($conversations as $conv)
<a href="{!! route('conversation', $conv) !!}" class="list-group-item @if (isset($conversation) && $conv->getKey() == $conversation->getKey())active @endif">
    <img src="{!! $conv->target()->getAvatarPath() !!}">

    <div class="media-body">
        <h4 class="list-group-item-heading">{{ $conv->target()->name }}</h4>
        <p class="list-group-item-text">
            {{ Str::limit(strip_tags($conv->lastMessage->text), 50) }}
        </p>

    </div>
</a>
@endforeach
</div>
