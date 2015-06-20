<hr>

<div class="row">
    <div class="col-sm-2 col-sm-offset-2">
        <ul>
            <li><a href="/" rel="nofollow">{{ trans('common.homepage') }}</a></li>
            <li><a href="{!! action('GroupController@showList') !!}" rel="nofollow">{{ trans('common.group list') }}</a></li>
        </ul>
    </div>

    <div class="col-sm-2">
        <ul>
            <li><a href="/guide" rel="nofollow">{{ trans('common.guide') }}</a></li>
            <li><a href="/ranking" rel="nofollow">{{ trans('common.ranking') }}</a></li>
        </ul>
    </div>

    <div class="col-sm-2">
        <ul>
            <li><a href="/rss" rel="nofollow">RSS</a></li>
            <li><a href="http://developers.strimoid.pl/" rel="nofollow">API</a></li>
        </ul>
    </div>

    <div class="col-sm-2">
        <ul>
            <li><a href="/" rel="nofollow">{{ trans('common.rules') }}</a></li>
            <li><a href="/contact" rel="nofollow">{{ trans('common.contact') }}</a></li>
        </ul>
    </div>
</div>

<hr>

<p class="pull-left">{{ trans('common.website uses') }} <a href="/cookies" rel="nofollow">{{ trans('common.cookie files') }}.</a></p>
<p class="pull-right toggle_night_mode">{{ trans('common.night mode') }} <span class="glyphicon glyphicon-adjust"></span></p>
