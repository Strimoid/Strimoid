<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Nazwa użytkownika</th>
        <th>Zablokowano</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>

    <?php $x = 0; ?>
    @foreach ($blockedUsers as $blockedUser)
        <?php $x++; ?>
        <tr>
            <td>{!! $x !!}</td>
            <td><a href="{!! route('user_profile', $blockedUser->name) !!}">{!! $blockedUser->name !!}</a></td>
            <td>{!! $blockedUser->created_at->diffForHumans() !!}</td>
            <td data-name="{!! $blockedUser->name !!}">{{--<button type="button" data-name="{!! $blockedUser->name !!}" class="btn btn-xs group_block_btn btn-danger">Zablokuj</button>--}}</td>
        </tr>
    @endforeach

    </tbody>
</table>
