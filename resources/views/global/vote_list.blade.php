@if ($votes)
<div class="hide {!! $class !!}">
    @foreach ($votes as $vote)
    <?php if (($up && !$vote['up']) || !$up && $vote['up']) continue; ?>
    <div>
        @if ($vote['up'])
        <i class="fa fa-arrow-up vote-up"></i>
        @else
        <i class="fa fa-arrow-down vote-down"></i>
        @endif

        {!! $vote['user_id'] !!}

        <span class="time">{!! Carbon::createFromTimeStamp($vote['created_at']->sec)->diffForHumans() !!}</span>
    </div>
    @endforeach
</div>
@endif
