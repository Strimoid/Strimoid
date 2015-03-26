<div class="comment_parent">
    <span>[<a href="{!! route('group_contents', $content->group) !!}" rel="nofollow">{!! $content->group->name !!}</a>]</span>
    <a href="{!! route('content_comments', $content) !!}">{{ $content->title }}</a>
</div>
