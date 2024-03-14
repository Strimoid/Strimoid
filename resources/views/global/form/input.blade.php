<?php

if ( ! isset($rows))
    $rows = 2;

if ( ! isset($class))
    $class = 'form-control';
else
    $class = 'form-control '. $class;

?>

<div class="form-group row @if ($errors->has($name)) has-error @endif">
    <label for="{{ $name }}" class="col-lg-3 control-label">{{ $label }}</label>

    <div class="col-lg-6">
        @if ($type === 'text')
            {{ html()->text($name)->class($class)->placeholder($label) }}
        @elseif ($type === 'textarea')
            {{ html()->textarea($name)->class($class)->placeholder($label)->rows($rows) }}
        @elseif ($type === 'email')
            {{ html()->email($name)->class($class)->placeholder($label) }}
        @elseif ($type === 'password')
            {{ html()->password($name)->class($class)->placeholder($label) }}
        @endif


        @if ($errors->has($name))
            <p class="help-block">{{ $errors->first($name) }}</p>
        @endif
    </div>
</div>
