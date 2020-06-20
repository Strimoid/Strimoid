<?php

if ( ! isset($rows))
    $rows = 2;

if ( ! isset($class))
    $class = 'form-control';
else
    $class = 'form-control '. $class;

?>

<div class="form-group row @if ($errors->has($name)) has-error @endif">
    <label for="{!! $name !!}" class="col-lg-3 control-label">{!! $label !!}</label>

    <div class="col-lg-6">
        @if ($type == 'text')
            {!! Form::text($name, null, ['class' => $class, 'placeholder' => $label]) !!}
        @elseif ($type == 'textarea')
            {!! Form::textarea($name, null, ['class' => $class, 'placeholder' => $label, 'rows' => $rows]) !!}
        @elseif ($type == 'email')
            {!! Form::email($name, null, ['class' => $class, 'placeholder' => $label]) !!}
        @elseif ($type == 'password')
            {!! Form::password($name, ['class' => $class, 'placeholder' => $label]) !!}
        @endif


        @if ($errors->has($name))
            <p class="help-block">{!! $errors->first($name) !!}</p>
        @endif
    </div>
</div>
