@extends('global.master')

@section('content')
<div class="row">
    {{ html()->form(action: action('ContentController@addContent'))->class(['col-lg-12', 'content_add_form'])->open() }}

    <input type="hidden" name="type" value="link">

    <p id="currentTab"></p>

        <div class="row" style="margin-bottom: 20px">
            <ul class="nav nav-tabs col-lg-6 offset-lg-3">
                <li class="nav-item w-50">
                    <a class="nav-link active" href="#link" data-bs-toggle="tab">
                        <i class="fa fa-link"></i>
                        @lang('common.add link')
                    </a>
                </li>
                <li class="nav-item w-50">
                    <a class="nav-link" href="#content" data-bs-toggle="tab">
                        <i class="fa fa-pencil"></i>
                        @lang('common.add content')
                    </a>
                </li>
            </ul>
        </div>

        @include('global.form.input_value', ['type' => 'text', 'class' => 'group_typeahead', 'name' => 'groupname', 'label' => trans('common.group'), 'value' => Input::get('group'), 'autocomplete' => 'off'])

        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade in active show" id="link">
                @include('global.form.input_value', ['type' => 'text', 'name' => 'url', 'label' => trans('common.url address'), 'value' => Input::get('url')])
            </div>
            <div class="tab-pane fade" id="content">
                @include('global.form.input', ['type' => 'textarea', 'class' => 'md_editor', 'name' => 'text', 'label' => trans('common.text'), 'rows' => 10])
            </div>
        </div>

    @include('global.form.input_value', ['type' => 'text', 'name' => 'title', 'label' => trans('common.title'), 'maxlength' => '128', 'value' => Input::get('title')])
    @include('global.form.input_value', ['type' => 'textarea', 'name' => 'description', 'label' => trans('common.description'), 'maxlength' => '255', 'value' => Input::get('description')])

    <div class="form-group row">
        <label class="col-lg-3 control-label">@lang('common.options')</label>

        <div class="col-lg-4">
            <div class="checkbox">
                <label>
                    {{ html()->checkbox('thumbnail', Input::get('thumbnail') !== 'no', 'on') }} @lang('common.thumbnail')
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {{ html()->checkbox('nsfw', Input::has('18'), 'on') }} @lang('common.nsfw')
                </label>
            </div>
            <div class="checkbox">
                <label>
                    {{ html()->checkbox('eng', Input::has('eng'), 'on') }} @lang('content.foreign language')
                </label>
            </div>
        </div>

        <div class="col-lg-2">
            <button type="submit" class="btn btn-primary pull-right">
                @lang('content.add content')
            </button>
        </div>
    </div>
    {{ html()->form()->close() }}
</div>
@stop

@section('scripts')
    <link href="/static/css/simplemde.min.css" rel="stylesheet">
    <script src="/static/js/simplemde.min.js"></script>

    <script>
        const editor = new SimpleMDE($('.md_editor')[0]);
        editor.render();
    </script>
@endsection

