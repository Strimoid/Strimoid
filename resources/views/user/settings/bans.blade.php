<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Nazwa grupy</th>
        <th>Zbanowano</th>
        <th>Powód</th>
    </tr>
    </thead>
    <tbody>

    <?php $x = 0; ?>
    @foreach ($bans as $ban)
        <?php $x++; ?>
        <tr>
            <td>{!! $x !!}</td>
            <td><a href="{!! route('group_contents', $ban->urlname) !!}">{!! $ban->name !!}</a></td>
            <td>{!! $ban->created_at->diffForHumans() !!}</td>
            <td>{{{ $ban->reason }}}</td>
        </tr>
    @endforeach

    </tbody>
</table>
