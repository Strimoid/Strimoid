@if (count($replies))
    @foreach ($replies as $reply)
        @include('widget', ['entry' => $reply, 'isReply' => true])
    @endforeach
@endif
