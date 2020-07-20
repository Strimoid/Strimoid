<div class="well group_search_widget">
    {!! Form::open(['action' => 'SearchController@search', 'method' => 'GET']) !!}
    <div class="input-group">
        {!! Form::text('q', '', ['class' => 'form-control', 'placeholder' => trans('common.search') . '...']) !!}

        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
