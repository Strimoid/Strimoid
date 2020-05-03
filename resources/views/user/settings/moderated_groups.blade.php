<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Nazwa grupy</th>
        <th>Dodano</th>
        <th>Subskrypcja</th>
    </tr>
    </thead>
    <tbody>

    <?php $x = 0; ?>
    @foreach ($moderatedGroups as $moderatedGroup)
        <?php $x++; ?>
        <tr>
            <td>{!! $x !!}</td>
            <td><a href="{!! route('group_contents', $moderatedGroup->urlname) !!}">{!! $moderatedGroup->name !!}</a></td>
            <td>{!! $moderatedGroup->created_at->diffForHumans() !!}</td>
            <td data-name="{!! $moderatedGroup->urlname !!}"><button type="button" data-name="{!! $moderatedGroup->urlname !!}" class="btn btn-xs group_subscribe_btn btn-success">Subskrybuj</button></td>
        </tr>
    @endforeach

    </tbody>
</table>
