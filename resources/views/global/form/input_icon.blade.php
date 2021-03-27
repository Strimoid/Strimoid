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
            <span class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-fw fa-{!! $icon !!}"></i>
                </span>
            </span>
            @endif

            @if ($type == 'text')
                {!! Form::text($name, null, $options) !!}
            @elseif ($type == 'textarea')
                {!! Form::textarea($name, null, Arr::add($options, 'rows', $rows)) !!}
            @elseif ($type == 'email')
                {!! Form::email($name, null, $options) !!}
            @elseif ($type == 'password')
                {!! Form::password($name, $options) !!}
            @endif
        </div>


        @if ($errors->has($name))
            <p class="help-block">{!! $errors->first($name) !!}</p>
        @endif
    </div>
</div>
