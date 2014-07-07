@extends('global.master')

@section('content')
<div class="row">
    {{ Form::open(array('action' => array('ContentController@editContent', $content->_id), 'class' => 'form-horizontal')) }}

    @include('global.form.input_value', array('type' => 'text', 'name' => 'title', 'label' => 'Nazwa treści', 'value' => $content->title))
    @include('global.form.input_value', array('type' => 'textarea', 'name' => 'description', 'label' => 'Opis treści', 'value' => $content->description))
    @if ($content->text)
        @include('global.form.input_value', array('type' => 'textarea', 'name' => 'text', 'label' => 'Twoja treść', 'rows' => 10, 'value' => $content->text_source))
    @else
        @include('global.form.input_value', array('type' => 'text', 'name' => 'url', 'label' => 'Adres URL', 'value' => $content->url))
    @endif

    <div class="form-group">
        <label class="col-lg-3 control-label">Dodatkowe opcje</label>

        <div class="col-lg-6">
            <div class="checkbox">
                <label>
                    {{ Form::checkbox('nsfw', 'on', Input::old('nsfw', $content->nsfw)) }} Treść +18
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {{ Form::checkbox('eng', 'on', Input::old('eng', $content->eng)) }} Treść w języku angielskim
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-6">
            <button type="submit" class="btn btn-default btn-primary pull-right">Zapisz zmiany</button>
        </div>
    </div>
    {{ Form::close() }}
</div>
@stop

