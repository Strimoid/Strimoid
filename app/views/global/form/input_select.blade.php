<div class="form-group @if ($errors->has($name)) has-error @endif">
    <label for="{!! $name !!}" class="col-lg-3 control-label">{!! $label !!}</label>

    <div class="col-lg-6">
        {!! Form::select($name, $options, $value, array('class' => 'form-control')) !!}
    </div>
</div>
