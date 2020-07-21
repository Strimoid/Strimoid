<div class="well content_sort_widget">
    <div class="btn-group btn-block">
        <div class="btn-group half-width">
            <button type="button" class="btn btn-light btn-block dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-sort"></i>
                @lang('common.sort')
                <span class="caret"></span>
            </button>
            <div class="dropdown-menu content_sort">
                <a class="dropdown-item action_link @if (!Input::has('sort')) selected @endif" data-sort="">
                    Domyślne
                </a>
                <a class="dropdown-item action_link @if (Input::get('sort') == 'uv') selected @endif" data-sort="uv">
                    Liczba UV
                </a>
                <a class="dropdown-item action_link @if (Input::get('sort') == 'comments') selected @endif" data-sort="comments">
                    Liczba komentarzy
                </a>
            </div>
        </div>

        <div class="btn-group half-width">
            <button type="button" class="btn btn-block btn-light dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-calendar"></i>
                @lang('common.filter')
                <span class="caret"></span>
            </button>
            <div class="dropdown-menu content_filter">
                <a class="dropdown-item action_link @if (!Input::has('time')) selected @endif" data-time="">
                    Wszystkie
                </a>

                <a class="dropdown-item action_link @if (Input::get('time') == '1d') selected @endif" data-time="1d">
                    Z ostatnich 24 godzin
                </a>

                <a class="dropdown-item action_link @if (Input::get('time') == '5d') selected @endif" data-time="5d">
                    Z ostatnich 5 dni
                </a>

                <a class="dropdown-item action_link @if (Input::get('time') == '30d') selected @endif" data-time="30d">
                    Z ostatnich 30 dni
                </a>
            </div>
        </div>
    </div>
</div>
