<div class="well group_search_widget">
    {{ html()->form('GET', action('SearchController@search'))->open() }}
    <div class="input-group">
        {{ html()->text('q')->class('form-control')->placeholder(trans('common.search') . '...') }}

        <div class="input-group-append">
            <button type="submit" class="btn btn-primary" aria-label="{{ trans('common.search') }}">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
    {{ html()->form()->close() }}
</div>
