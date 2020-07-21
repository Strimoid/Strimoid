{!! Form::open(['action' => 'SettingsController@saveSettings', 'class' => 'form-horizontal', 'style' => 'margin-top: 20px']) !!}

<div class="form-group row">
    <label class="col-lg-3 control-label">Opcje</label>

    <div class="col-lg-6">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('enter_send', 'on', setting('enter_send')) !!} Wysyłaj treści/komentarze enterem
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('homepage_subscribed', 'on', setting('homepage_subscribed')) !!} Subskrybowane jako strona główna serwisu
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-lg-3 control-label">Powiadomienia</label>

    <div class="col-lg-6">
        <div class="checkbox">
            <label>
                <input name="browser_notifications" type="checkbox" value="on"> Wyświetlaj powiadomienia w przeglądarce
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notifications_sound', 'on', setting('notifications_sound')) !!} Odtwarzaj dźwięk po otrzymaniu powiadomienia
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('notifications[auto_read]', 'on', setting('notifications.auto_read')) !!} Automatycznie oznaczaj powiadomienia jako przeczytane
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-lg-3 control-label">Wygląd</label>

    <div class="col-lg-6">
        <div class="checkbox">
            <label>
                {!! Form::checkbox('pin_navbar', 'on', setting('pin_navbar'))  !!} Przypnij górny pasek
            </label>
        </div>
        <div class="checkbox">
            <label>
                {!! Form::checkbox('disable_groupstyles', 'on', setting('disable_groupstyles'))  !!} Wyłącz style grup
            </label>
        </div>
    </div>
</div>

@include('global.form.input_select', ['name' => 'contents_per_page', 'label' => 'Ilość treści na stronę', 'value' => setting('contents_per_page'), 'options' => app('settings')->getOptions('contents_per_page')])
@include('global.form.input_select', ['name' => 'entries_per_page', 'label' => 'Ilość wpisów na stronę', 'value' => setting('entries_per_page'), 'options' => app('settings')->getOptions('entries_per_page')])

@include('global.form.input_select', ['name' => 'language', 'label' => 'Język', 'value' => setting('language'), 'options' => setting()->getOptions('language')])
@include('global.form.input_select', ['name' => 'timezone', 'label' => 'Strefa czasowa', 'value' => setting('timezone'), 'options' => setting()->getOptions('timezone')])

@include('global.form.input_value', ['type' => 'text', 'name' => 'css_style', 'label' => 'Własny styl CSS', 'value' => setting('css_style'), 'placeholder' => 'http://link.do/stylu.css'])

@include('global.form.submit')

{!! Form::close() !!}
