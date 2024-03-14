@extends('global.master')

@section('content')
    {{ html()->form(action: action('Content\ThumbnailController@saveThumbnail'))->class(['form-horizontal'])->open() }}
    <input type="hidden" name="id" value="{!! $content->hashId() !!}">

    <select class="image-picker" name="thumbnail">
        <option value=""></option>
        @foreach ($thumbnails as $id => $thumbnail)
            <option data-img-src="{!! $thumbnail !!}" value="{!! $id !!}"></option>
        @endforeach
    </select>

    <div class="form-group col-lg-12">
        <button type="submit" class="btn btn-secondary btn-primary pull-right">Zapisz</button>
    </div>

    {{ html()->form()->close() }}
@stop

@section('sidebar')

@stop
