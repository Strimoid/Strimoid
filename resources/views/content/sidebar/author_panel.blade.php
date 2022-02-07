@can('remove', $content)
<div class="well">
    <h4>@lang('common.options')</h4>

    <div class="btn-group">
        @can('edit', $content)
            <a href="{!! action('ContentController@showEditForm', $content) !!}" class="btn btn-sm btn-secondary">
                @lang('common.edit')
            </a>
        @endcan

        <a href="{!! action('Content\ThumbnailController@chooseThumbnail', $content) !!}" class="btn btn-sm btn-secondary">
            @lang('content.change thumbnail')
        </a>
        @can('remove', $content)
        <a class="btn btn-sm btn-danger content_remove_btn" data-id="{!! $content->hashId() !!}">
            @lang('common.delete')
        </a>
        @endcan
    </div>
</div>
@endcan
