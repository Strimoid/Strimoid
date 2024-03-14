<?php

if ( ! isset($rows)) $rows = 2;

if ( ! isset($class))
    $class = 'form-control';
else
    $class = 'form-control '. $class;

$options = [
    'class' => $class,
    'placeholder' => $label
];

?>

<div class="form-group row @if ($errors->has($name)) has-error @endif">
    <label for="{!! $name !!}" class="col-lg-3 control-label">{!! $label !!}</label>

    <div class="col-lg-6">
        <div class="input-group">
            @if ($icon ?? null)
                <span class="input-group-text">
                    <i class="fa fa-fw fa-{!! $icon !!}"></i>
                </span>
            @endif

            @if ($type === 'text')
                {{ html()->text($name)->attributes($options) }}
            @elseif ($type === 'textarea')
                {{ html()->textarea($name)->attributes($options)->rows($rows) }}
            @elseif ($type === 'email')
                {{ html()->email($name)->attributes($options) }}
            @elseif ($type === 'password')
                {{ html()->password($name)->attributes($options) }}
            @endif
        </div>


        @if ($errors->has($name))
            <p class="help-block">{!! $errors->first($name) !!}</p>
        @endif
    </div>
</div>
