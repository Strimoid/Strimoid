<div class="tab-pane fade" id="style">
    {!! Form::open(['action' => ['GroupController@saveStyle', $group->urlname], 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

    @include('global.form.input_value', ['type' => 'textarea', 'class' => 'css_editor', 'name' => 'css', 'label' => 'Styl CSS', 'rows' => '20', 'value' => $css])

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-6">
            <button type="submit" class="btn btn-primary">Zapisz</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
