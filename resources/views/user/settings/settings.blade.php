{{ html()->form(action: action('SettingsController@saveSettings'))->class(['form-horizontal', 'mt-5'])->open() }}

<div class="form-group row">
    <label class="col-lg-3 control-label">Opcje</label>

    <div class="col-lg-6">
        <div class="checkbox">
            <label>
                {{ html()->checkbox('enter_send', setting('enter_send'), 'on') }} Wysyłaj treści/komentarze enterem
            </label>
        </div>
        <div class="checkbox">
            <label>
                {{ html()->checkbox('homepage_subscribed', setting('homepage_subscribed'), 'on') }} Subskrybowane jako strona główna serwisu
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
                {{ html()->checkbox('notifications_sound', setting('notifications_sound'), 'on') }} Odtwarzaj dźwięk po otrzymaniu powiadomienia
            </label>
        </div>
        <div class="checkbox">
            <label>
                {{ html()->checkbox('notifications[auto_read]', setting('notifications.auto_read'), 'on') }} Automatycznie oznaczaj powiadomienia jako przeczytane
            </label>
        </div>
    </div>
</div>

<div class="form-group row">
    <label class="col-lg-3 control-label">Wygląd</label>

    <div class="col-lg-6">
        <div class="checkbox">
            <label>
                {{ html()->checkbox('pin_navbar', setting('pin_navbar'), 'on') }} Przypnij górny pasek
            </label>
        </div>
        <div class="checkbox">
            <label>
                {{ html()->checkbox('disable_groupstyles', setting('disable_groupstyles'), 'on') }} Wyłącz style grup
            </label>
        </div>
    </div>
</div>

@include('global.form.input_select', ['name' => 'contents_per_page', 'label' => 'Ilość treści na stronę', 'value' => setting('contents_per_page'), 'options' => app('settings')->getOptions('contents_per_page')])
@include('global.form.input_select', ['name' => 'entries_per_page', 'label' => 'Ilość wpisów na stronę', 'value' => setting('entries_per_page'), 'options' => app('settings')->getOptions('entries_per_page')])

@include('global.form.input_select', ['name' => 'language', 'label' => 'Język', 'value' => setting('language'), 'options' => setting()->getOptions('language')])
@include('global.form.input_select', ['name' => 'timezone', 'label' => 'Strefa czasowa', 'value' => setting('timezone'), 'options' => setting()->getOptions('timezone')])

@include('global.form.input_value', ['type' => 'text', 'name' => 'css_style', 'label' => 'Własny styl CSS', 'value' => setting('css_style'), 'placeholder' => 'https://link.do/stylu.css'])

@include('global.form.submit')

{{ html()->form()->close() }}
