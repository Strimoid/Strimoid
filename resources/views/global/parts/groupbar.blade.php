<div class="groupbar groupbar-{!! $navbarClass !!}">
    <ul>
        <li><a href="/g/all" rel="nofollow">{{ trans('common.all') }}</a></li>

        @if (Auth::check())
            <?php $subscriptions = Auth::user()->subscribedGroups()->lists('urlname')->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE); ?>

            <li class="dropdown subscribed_dropdown">
                <a href="/g/subscribed" class="dropdown-toggle" data-hover="dropdown" data-hover-delay="250">Subskrybowane</a><b class="caret"></b>

                <ul class="dropdown-menu">
                    @foreach ($subscriptions as $subscription)
                        <li><a href="{!! route('group_contents', $subscription) !!}">{!! $subscription !!}</a></li>
                    @endforeach

                    @if (!$subscriptions)
                        <li><a href="{!! action('GroupController@showList') !!}">{{ trans('common.group list') }}</a></li>
                    @endif
                </ul>
            </li>

            <?php $moderatedGroups = Auth::user()->moderatedGroups()->lists('urlname')->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE);  ?>

            <li class="dropdown moderated_dropdown">
                <a href="/g/moderated" class="dropdown-toggle" data-hover="dropdown" data-hover-delay="250">Moderowane</a><b class="caret"></b>

                <ul class="dropdown-menu">
                    @foreach ($moderatedGroups as $moderatedGroup)
                        <li><a href="{!! route('group_contents', $moderatedGroup) !!}">{!! $moderatedGroup !!}</a></li>
                    @endforeach

                    @if (!$moderatedGroups)
                        <li><a href="{!! action('GroupController@showCreateForm') !!}">Zakładanie grupy</a></li>
                    @endif
                </ul>
            </li>

            <?php $observedUsers = Auth::user()->followedUsers()->lists('name')->sortBy(null, SORT_NATURAL | SORT_FLAG_CASE);  ?>

            <li class="dropdown observed_dropdown">
                <a href="/g/observed" class="dropdown-toggle" data-hover="dropdown" data-hover-delay="250">Obserwowani</a><b class="caret"></b>

                <ul class="dropdown-menu">
                    @foreach ($observedUsers as $observedUser)
                        <li><a href="{!! route('user_profile', $observedUser) !!}">{!! $observedUser !!}</a></li>
                    @endforeach
                </ul>
            </li>

            @foreach (Auth::user()->folders as $cfolder)
                <?php $folderGroups = $cfolder->groups; natcasesort($folderGroups); ?>

                <li class="dropdown folder_dropdown">
                    <a href="{!! route('user_folder_contents', [$cfolder->user->_id, $cfolder->_id]) !!}" class="dropdown-toggle" data-hover="dropdown">{{{ $cfolder->name }}}</a><b class="caret"></b>

                    <ul class="dropdown-menu">
                        @foreach ($folderGroups as $folderGroup)
                            <li><a href="{!! route('group_contents', $folderGroup) !!}">{!! $folderGroup !!}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endforeach

            <li><a href="/g/saved">Zapisane</a></li>

        @endif

        @foreach ($popularGroups as $pgroup)
            <li><a href="/g/{!! $pgroup['urlname'] !!}">{!! $pgroup['name'] !!}</a></li>
        @endforeach

        <li class="group_list_link">
            <a href="/groups/list">
                <span class="glyphicon glyphicon-th-list"></span> {{ trans('common.group list') }}
            </a>
        </li>
    </ul>
</div>
