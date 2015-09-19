@if (Auth::check() && $content->canEdit(Auth::user()))
<div class="well">
    <h4>Opcje</h4>

    <div class="btn-group">
        <a href="{!! action('ContentController@showEditForm', $content) !!}" class="btn btn-sm btn-secondary">
            Edytuj treść
        </a>
        <a href="{!! action('ContentController@chooseThumbnail', $content) !!}" class="btn btn-sm btn-secondary">
            Zmień miniaturkę
        </a>
        <a class="btn btn-sm btn-danger content_remove_btn" data-id="{!! $content->hashId() !!}">
            Usuń
        </a>
    </div>
</div>
@endif
