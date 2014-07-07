<!doctype html>

<html lang="pl">
<head>
    <meta charset="utf-8">

    <title>{{{ $content->title }}} - Strimoid.pl</title>

    <link rel="stylesheet" href="/static/css/frame.css">
</head>

<body>

<div id="wrapper">
    <nav>
        <h1>Strimoid</h1>

        <h1>
            <a href="{{ route('content_comments', $content->_id) }}">{{{ $content->title }}}</a>

        </h1>

        <ul>
            <li>{{ $content->uv }} uv</li>
            <li>{{ $content->dv }} dv</li>
        </ul>
    </nav>

    <div id="iframe">
        <iframe src="{{{ $content->url }}}">
            <meta http-equiv="refresh" content="0;url={{{ $content->url }}}">
        </iframe>
    </div>
</div>

</body>
</html>