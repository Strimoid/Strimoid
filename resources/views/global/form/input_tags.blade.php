<?php

if ( ! isset($placeholder))
    $placeholder = $label;

if ( ! isset($noun))
    $noun = 'Tagi';

?>

<div class="form-group @if ($errors->has($name)) has-error @endif">
    <label for="{!! $name !!}" class="col-lg-3 control-label">{!! $label !!}</label>

    <div class="col-lg-6">
        {!! Form::text($name, implode(',', (array) $tags), [
            'class' => 'form-control',
            'placeholder' => $placeholder,
            'data-role' => 'tagsinput'
        ]) !!}

        @if($errors->has($name))
            <p class="help-block">{!! $errors->first($name) !!}</p>
        @else
            <p class="help-block">{!! $noun !!} oddzielane są za pomocą entera.</p>
        @endif
    </div>
</div>
