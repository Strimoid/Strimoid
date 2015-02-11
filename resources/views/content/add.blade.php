@extends('global.master')

@section('content')
<div class="row">
    {!! Form::open(array('action' => 'ContentController@addContent', 'class' => 'form-horizontal content_add_form')) !!}
    <input type="hidden" name="type" value="link">

    <p id="currentTab"></p>

    <div>
        <div class="row" style="margin-bottom: 20px">
            <ul id="myTab" class="nav nav-tabs col-lg-offset-3 col-lg-6">
                <li class="active"><a href="#link" data-toggle="tab"><span class="glyphicon glyphicon-link"></span>  Dodaj link</a></li>
                <li><a href="#content" data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span>  Dodaj własną treść</a></li>
            </ul>
        </div>

        @include('global.form.input_value', array('type' => 'text', 'class' => 'group_typeahead', 'name' => 'groupname', 'label' => 'Grupa', 'value' => Input::get('group')))

        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active" id="link">
                @include('global.form.input_value', array('type' => 'text', 'name' => 'url', 'label' => 'Adres URL', 'value' => Input::get('url')))
            </div>
            <div class="tab-pane fade" id="content">
                @include('global.form.input', array('type' => 'textarea', 'class' => 'md_editor', 'name' => 'text', 'label' => 'Twoja treść', 'rows' => 10))
            </div>
        </div>
    </div>

    @include('global.form.input_value', array('type' => 'text', 'name' => 'title', 'label' => 'Nazwa treści', 'maxlength' => '128', 'value' => Input::get('title')))
    @include('global.form.input_value', array('type' => 'textarea', 'name' => 'description', 'label' => 'Opis treści', 'maxlength' => '255', 'value' => Input::get('description')))

    <div class="form-group">
        <label class="col-lg-3 control-label">Dodatkowe opcje</label>

        <div class="col-lg-6">
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('thumbnail', 'on', Input::get('thumbnail') == 'no' ? false : true) !!} Miniaturka
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('nsfw', 'on', Input::has('18')) !!} Treść +18
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('eng', 'on', Input::has('eng')) !!} Treść w języku angielskim
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-6">
            <button type="submit" class="btn btn-default btn-primary pull-right">Dodaj treść</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@stop

@section('sidebar')
<div class="well">
    <p>Dołączenie do społeczności {!! Config::get('app.site_name') !!} pozwoli Ci na pełny udział w życiu serwisu
        oraz możliwość dostosowania go do własnych upodobań.</p>
    <p>Zapraszamy!</p>
</div>
@stop
