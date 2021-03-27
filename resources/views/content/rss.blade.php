{!! '<'.'?'.'xml version="1.0" encoding="UTF-8" ?>'."\n" !!}

<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>Strm: strona główna</title>
        <link>https://strm.pl/</link>
        <description>Ostatnio popularne treści na portalu Strm.pl</description>
        <atom:link href="{{ request()->url() }}" rel="self"></atom:link>
        <image>
            <url>https://strm.pl/static/img/logo.png</url>
            <title>{{ config('app.name') }}</title>
            <link>https://strm.pl/</link>
        </image>
        @foreach($contents as $content)
            <item>
                <title>{!! $content->title !!}</title>
                <link>{{ route('content_comments', $content->getKey()) }}</link>
                <guid isPermaLink="true">{{ route('content_comments', $content->getKey()) }}</guid>
                <description>{!! $content->description !!}</description>
                <dc:creator xmlns:dc="http://purl.org/dc/elements/1.1/">{{ $content->user->name }}</dc:creator>
                <pubDate>{{ $content->created_at->format(\DateTime::RSS) }}</pubDate>
            </item>
        @endforeach
    </channel>
</rss>
