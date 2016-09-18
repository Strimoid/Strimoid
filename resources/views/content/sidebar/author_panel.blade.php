@if (Auth::check() && $content->canEdit(Auth::user()))
<div class="well">
    <h4>@lang('common.options')</h4>

    <div class="btn-group">
        <a href="{!! action('ContentController@showEditForm', $content) !!}" class="btn btn-sm btn-secondary">
            @lang('common.edit')
        </a>
        <a href="{!! action('Content\ThumbnailController@chooseThumbnail', $content) !!}" class="btn btn-sm btn-secondary">
            @lang('content.change thumbnail')
        </a>
        <a class="btn btn-sm btn-danger content_remove_btn" data-id="{!! $content->hashId() !!}">
            @lang('common.delete')
        </a>
    </div>
</div>
@endif
