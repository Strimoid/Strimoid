@foreach ($replies as $reply)
    @include('comments.widget', ['comment' => $reply, 'isReply' => true])
@endforeach
