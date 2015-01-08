@if (count($replies))
    @foreach ($replies as $reply)
        @include('entries.widget', ['entry' => $reply, 'isReply' => true])
    @endforeach
@endif
