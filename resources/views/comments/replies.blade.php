@foreach ($replies as $reply)
    @include('widget', ['comment' => $reply, 'isReply' => true])
@endforeach
