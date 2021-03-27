<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Nazwa grupy</th>
        <th>Zasubskrybowano</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>

    <?php $x = 0; ?>
    @foreach ($subscribedGroups as $subscribedGroup)
        <?php $x++; ?>
        <tr>
            <td>{!! $x !!}</td>
            <td><a href="{!! route('group_contents', $subscribedGroup->urlname) !!}">{!! $subscribedGroup->name !!}</a></td>
            <td>{!! $subscribedGroup->created_at->diffForHumans() !!}</td>
            <td><button type="button" data-name="{!! $subscribedGroup->urlname !!}" class="btn btn-xs group_subscribe_btn btn-success">Subskrybuj</button></td>
        </tr>
    @endforeach

    </tbody>
</table>
