@extends('global.master')

@section('content')
<div class="row">
    {!! Form::open(['action' => ['ContentController@editContent', $content], 'class' => 'form-horizontal']) !!}

    @include('global.form.input_value', ['type' => 'text', 'name' => 'title', 'label' => 'Nazwa treści', 'value' => $content->title])
    @include('global.form.input_value', ['type' => 'textarea', 'name' => 'description', 'label' => 'Opis treści', 'value' => $content->description])
    @if ($content->text)
        @include('global.form.input_value', ['type' => 'textarea', 'name' => 'text', 'label' => 'Twoja treść', 'rows' => 10, 'value' => $content->text_source])
    @else
        @include('global.form.input_value', ['type' => 'text', 'name' => 'url', 'label' => 'Adres URL', 'value' => $content->url])
    @endif

    <div class="form-group">
        <label class="col-lg-3 control-label">Dodatkowe opcje</label>

        <div class="col-lg-6">
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('nsfw', 'on', $content->nsfw) !!} Treść +18
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('eng', 'on', $content->eng) !!} Treść w języku angielskim
                </label>
            </div>
        </div>
    </div>

    @include('global.form.submit', ['label' => 'Zapisz zmiany'])

    {!! Form::close() !!}
</div>
@stop

