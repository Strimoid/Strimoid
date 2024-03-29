<li class="nav-item dropdown notifications_dropdown">
    <a href="#" data-bs-toggle="dropdown">
        <span class="fa fa-globe notifications_icon @if ($newNotificationsCount > 0) notifications_icon_new @endif"></span>
        <span class="badge @if (!$newNotificationsCount) hide @endif">{{ $newNotificationsCount }}</span>
    </a>

    <div class="dropdown-menu dropdown-menu-right notifications" data-new-notifications="{{ (int) $newNotificationsCount }}">
        <div class="notifications_scroll">
            <div class="notifications_list">
                @foreach ($notifications as $notification)
                    <a href="{!! $notification->url !!}" class="@if (!$notification->read) new @endif" data-id="{{ $notification->hashId() }}">
                        @if ($notification->user)
                            <img src="{!! $notification->thumbnail_path !!}" class="pull-left">
                        @endif

                        <div class="media-body">
                            {{ $notification->title }}

                            <br>
                            <small class="pull-left">
                                {{ $notification->type }}
                            </small>
                            <small class="pull-right">
                                <time pubdate title="{!! $notification->getLocalTime() !!}">{{ $notification->created_at->diffForHumans() }}</time>
                            </small>
                        </div>

                        <div class="clearfix"></div>
                    </a>
                @endforeach

                @if (!count($notifications))
                    <a>{{ trans('notifications.empty') }}</a>
                @endif
            </div>
        </div>

        <div class="notifications_footer">
            <a href="/notifications">{{ trans('notifications.all') }}</a>
            <a class="mark_as_read_link action_link pull-right">{{ trans('notifications.mark as read') }}</a>
        </div>
    </div>
</li>
