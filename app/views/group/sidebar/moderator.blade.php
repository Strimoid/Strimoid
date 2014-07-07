@if (isset($group) && Auth::check() && Auth::user()->isAdmin($group))
<div class="well group_admin_widget">
    {{--<h4>Opcje moderatora</h4>--}}

    <div class="btn-group">
        <a href="{{ route('group_settings', $group->urlname) }}">
            <button type="button" class="btn btn-sm btn-default">Ustawienia</button>
        </a>
    </div>
</div>
@endif
