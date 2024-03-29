{{ html()->form()->class(['form', 'entry_add_form', 'enter_send', 'entry_add'])->open() }}
<div class="panel-default entry">
    <div class="entry_avatar">
        <img src="{!! Auth::user()->getAvatarPath() !!}">
    </div>

    <div class="entry_text">
        <div class="form-group @if ($errors->has('text')) has-error @endif col-lg-12">
            {{ html()->textarea('text')->class('form-control')->placeholder('Treść wpisu...')->rows(2) }}

            @if($errors->has('text'))
                <p class="help-block">{!! $errors->first('text') !!}</p>
            @endif
        </div>

        <div class="form-group col-lg-12 pull-right @if ($errors->has('groupname')) has-error @endif">
            <div class="input-group flex-nowrap">
                {{ html()->text('groupname', $suggestedGroup)->class(['form-control', 'group_typeahead'])->placeholder('podaj nazwę grupy...')->attribute('autocomplete', 'off') }}

                <button type="submit" class="btn btn-primary">
                    @lang('common.add')
                </button>
            </div>

            @if($errors->has('groupname'))
                <p class="help-block">{{ $errors->first('groupname') }}</p>
            @endif
        </div>
    </div>
</div>
<div class="clearfix"></div>
{{ html()->form()->close() }}
