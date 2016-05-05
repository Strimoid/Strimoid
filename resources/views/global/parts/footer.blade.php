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
            <li><a href="https://developers.strm.pl" rel="nofollow">API</a></li>
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

<div class="pull-left">
    {{ trans('common.website uses') }} <a href="/cookies" rel="nofollow">{{ trans('common.cookie files') }}.</a>
</div>
<div class="pull-right">
    <a class="toggle_night_mode">
        <i class="fa fa-adjust"></i>
        {{ trans('common.night mode') }}
    </a>

    <div class="social-links">
        <a href="//www.facebook.com/Strimoid">
            <i class="fa fa-facebook-square"></i>
        </a>
        <a href="//twitter.com/strimoid">
            <i class="fa fa-twitter-square"></i>
        </a>
        <a href="//github.com/strimoid/strimoid">
            <i class="fa fa-github-square"></i>
        </a>
    </div>
</div>


