@if (isset($group) && $group instanceof Strimoid\Models\Group
 && Auth::check() && Auth::user()->isAdmin($group))
<div class="well group_admin_widget">
    <div class="btn-group">
        <a href="{!! route('group_settings', $group->urlname) !!}">
            <button type="button" class="btn btn-sm btn-default">Ustawienia</button>
        </a>
    </div>
</div>
@endif
