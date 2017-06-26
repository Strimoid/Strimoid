<?php

if ( ! isset($rows)) $rows = 2;
if ( ! isset($value)) $value = null;

if ( ! isset($class))
    $class = 'form-control';
else
    $class = 'form-control '. $class;

if ( ! isset($placeholder))
    $placeholder = $label;

$options = [
    'class' => $class,
    'placeholder' => $placeholder
];

?>

<div class="form-group row @if ($errors->has($name)) has-error @endif">
    <label for="{!! $name !!}" class="col-lg-3 control-label">{!! $label !!}</label>

    <div class="col-lg-6">
        @if ($type == 'text')
            {!! Form::text($name, $value, $options) !!}
        @elseif ($type == 'textarea')
            {!! Form::textarea($name, $value, array_add($options, 'rows', $rows)) !!}
        @elseif ($type == 'email')
            {!! Form::email($name, $value, $options) !!}
        @elseif ($type == 'password')
            {!! Form::password($name, $options) !!}
        @endif

        @if ($errors->has($name))
        <p class="help-block">{!! $errors->first($name) !!}</p>
        @endif
    </div>
</div>
