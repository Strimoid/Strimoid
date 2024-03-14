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
        @if ($type === 'text')
            {{ html()->text($name, $value)->class('form-control')->attributes($options) }}
        @elseif ($type === 'textarea')
            {{ html()->textarea($name, $value)->attributes($options)->rows($rows) }}
        @elseif ($type === 'email')
            {{ html()->email($name, $value)->attributes($options) }}
        @elseif ($type === 'password')
            {{ html()->password($name, $value)->attributes($options) }}
        @endif

        @if ($errors->has($name))
        <p class="help-block">{!! $errors->first($name) !!}</p>
        @endif
    </div>
</div>
