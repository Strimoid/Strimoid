@if ($votes)
<div class="hide {!! $class !!}">
    @foreach ($votes as $vote)
    <?php if (($up && !$vote['up']) || !$up && $vote['up']) continue; ?>
    <div>
        @if ($vote['up'])
        <span class="glyphicon glyphicon-arrow-up vote-up"></span>
        @else
        <span class="glyphicon glyphicon-arrow-down vote-down"></span>
        @endif

        {!! $vote['user_id'] !!}

        <span class="time">{!! Carbon::createFromTimeStamp($vote['created_at']->sec)->diffForHumans() !!}</span>
    </div>
    @endforeach
</div>
@endif
