<div class="well group_search_widget">
    {!! Form::open(array('action' => 'SearchController@search', 'method' => 'GET')) !!}
    <div class="input-group">
        {!! Form::text('q', '', array('class' => 'form-control', 'placeholder' => 'podaj wyszukiwaną frazę...')) !!}

        <div class="input-group-btn">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-search"></span>
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
