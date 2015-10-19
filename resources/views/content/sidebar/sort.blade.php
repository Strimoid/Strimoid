<div class="well content_sort_widget">
    <div class="btn-group btn-block">
        <div class="btn-group half-width">
            <button type="button" class="btn btn-secondary btn-block dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-sort"></i>
                @lang('common.sort')
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu content_sort">
                <li>
                    <a class="action_link @if (!Input::has('sort')) selected @endif" data-sort="">Domyślne</a>
                </li>
                <li>
                    <a class="action_link @if (Input::get('sort') == 'uv') selected @endif" data-sort="uv">Liczba UV</a>
                </li>
                <li>
                    <a class="action_link @if (Input::get('sort') == 'comments') selected @endif" data-sort="comments">Liczba komentarzy</a>
                </li>
            </ul>
        </div>

        <div class="btn-group half-width">
            <button type="button" class="btn btn-block btn-secondary dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-calendar"></i>
                @lang('common.filter')
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu content_filter">
                <li>
                    <a class="action_link @if (!Input::has('time')) selected @endif" data-time="">Wszystkie</a>
                </li>
                <li>
                    <a class="action_link @if (Input::get('time') == '1d') selected @endif" data-time="1d">Z ostatnich 24 godzin</a>
                </li>
                <li>
                    <a class="action_link @if (Input::get('time') == '5d') selected @endif" data-time="5d">Z ostatnich 5 dni</a>
                </li>
                <li>
                    <a class="action_link @if (Input::get('time') == '30d') selected @endif" data-time="30d">Z ostatnich 30 dni</a>
                </li>
            </ul>
        </div>
    </div>
</div>
