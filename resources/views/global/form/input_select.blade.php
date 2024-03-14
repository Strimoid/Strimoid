<div class="form-group row @if ($errors->has($name)) has-error @endif">
    <label for="{!! $name !!}" class="col-lg-3 control-label">{!! $label !!}</label>

    <div class="col-lg-6">
        {{ html()->select($name, $options, $value)->class('form-control') }}
    </div>
</div>
