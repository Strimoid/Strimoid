<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Nazwa grupy</th>
        <th>Zablokowano</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>

    <?php $x = 0; ?>
    @foreach ($blockedGroups as $blockedGroup)
        <?php $x++; ?>
        <tr>
            <td>{!! $x !!}</td>
            <td><a href="{!! route('group_contents', $blockedGroup->urlname) !!}">{!! $blockedGroup->name !!}</a></td>
            <td>{!! $blockedGroup->created_at->diffForHumans() !!}</td>
            <td data-name="{!! $blockedGroup->urlname !!}"><button type="button" data-name="{!! $blockedGroup->urlname !!}" class="btn btn-xs group_block_btn btn-danger">Blokuj</button></td>
        </tr>
    @endforeach

    </tbody>
</table>
