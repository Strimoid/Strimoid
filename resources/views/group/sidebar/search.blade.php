<div class="well group_search_widget">
    {!! Form::open(array('action' => 'SearchController@search', 'method' => 'GET')) !!}
    <div class="input-group">
        {!! Form::text('q', '', array('class' => 'form-control', 'placeholder' => 'podaj wyszukiwaną frazę...')) !!}

        <div class="input-group-btn">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
