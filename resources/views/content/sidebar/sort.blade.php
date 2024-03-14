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
                    {{ ucfirst(trans('common.sorting.default')) }}
                </a>
                <a class="dropdown-item action_link @if (Input::get('sort') === 'uv') selected @endif" data-sort="uv">
                    {{ ucfirst(trans('common.sorting.number_of_uv')) }}
                </a>
                <a class="dropdown-item action_link @if (Input::get('sort') === 'comments') selected @endif" data-sort="comments">
                    {{ ucfirst(trans('common.sorting.number_of_comments')) }}
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
                    {{ ucfirst(trans('common.filtering.all')) }}
                </a>

                <a class="dropdown-item action_link @if (Input::get('time') === '1d') selected @endif" data-time="1d">
                    {{ ucfirst(trans('common.filtering.last_24_hours')) }}
                </a>

                <a class="dropdown-item action_link @if (Input::get('time') === '5d') selected @endif" data-time="5d">
                    {{ ucfirst(trans('common.filtering.last_5_days')) }}
                </a>

                <a class="dropdown-item action_link @if (Input::get('time') === '30d') selected @endif" data-time="30d">
                    {{ ucfirst(trans('common.filtering.last_30_days')) }}
                </a>
            </div>
        </div>
    </div>
</div>
