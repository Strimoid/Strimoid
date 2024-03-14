<div class="tab-pane fade" id="style">
    {{ html()->form(action: action('GroupController@saveStyle', $group->urlname))->class(['form-horizontal', 'mt-5'])->open() }}

    @include('global.form.input_value', ['type' => 'textarea', 'class' => 'css_editor', 'name' => 'css', 'label' => 'Styl CSS', 'rows' => '20', 'value' => $css])

    <div class="form-group">
        <div class="col-lg-6 offset-lg-3">
            <button type="submit" class="btn btn-primary">Zapisz</button>
        </div>
    </div>
    {{ html()->form()->close() }}
</div>
