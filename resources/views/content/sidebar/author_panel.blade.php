@if (Auth::check() && $content->canEdit(Auth::user()))
<div class="well">
    <h4>Opcje</h4>

    <div class="btn-group">
        <a href="{!! action('ContentController@showEditForm', $content->_id) !!}" class="btn btn-sm btn-default">
            Edytuj treść
        </a>
        <a href="{!! action('ContentController@chooseThumbnail', $content->_id) !!}" class="btn btn-sm btn-default">
            Zmień miniaturkę
        </a>
        <a class="btn btn-sm btn-danger content_remove_btn" data-id="{!! $content->_id !!}">
            Usuń
        </a>
    </div>
</div>
@endif
