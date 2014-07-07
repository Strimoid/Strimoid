<?php

if (!isset($rows))
    $rows = 2;

if (!isset($class))
    $class = 'form-control';
else
    $class = 'form-control '. $class;

if (!isset($placeholder))
    $placeholder = $label;

?>

<div class="form-group @if ($errors->has($name)) has-error @endif">
    <label for="{{ $name }}" class="col-lg-3 control-label">{{ $label }}</label>

    <div class="col-lg-6">
        @if ($errors->has($name))

        @if ($type == 'text')
            {{ Form::text($name, Input::old($name), array('class' => $class, 'placeholder' => $placeholder)) }}
        @elseif ($type == 'textarea')
            {{ Form::textarea($name, Input::old($name), array('class' => $class, 'placeholder' => $placeholder, 'rows' => $rows)) }}
        @elseif ($type == 'email')
            {{ Form::email($name, Input::old($name), array('class' => $class, 'placeholder' => $placeholder)) }}
        @endif

        @else

        @if ($type == 'text')
            {{ Form::text($name, $value, array('class' => $class, 'placeholder' => $placeholder)) }}
        @elseif ($type == 'textarea')
            {{ Form::textarea($name, $value, array('class' => $class, 'placeholder' => $placeholder, 'rows' => $rows)) }}
        @elseif ($type == 'email')
            {{ Form::email($name, $value, array('class' => $class, 'placeholder' => $placeholder)) }}
        @endif

        @endif

        @if ($errors->has($name))
        <p class="help-block">{{ $errors->first($name) }}</p>
        @endif
    </div>
</div>
