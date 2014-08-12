@extends('global.master')

@section('content')

<h1 style="margin-top: 0">Formatowanie tekstu</h1>

<a name="markdown"></a>
<table class="table md">
    <thead>
    <tr>
        <th class="col-sm-6">Przykład</th>
        <th class="col-sm-6">Wynik</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>*test*<br>_test_</td>
        <td><em>test</em><br><em>test</em></td>
    </tr>
    <tr>
        <td>**test**<br>__test__</td>
        <td><strong>test</strong><br><strong>test</strong></td>
    </tr>
    <tr>
        <td>~~test~~</td>
        <td><del>test</del></td>
    </tr>
    <tr>
        <td>http://strimoid.pl</td>
        <td><a href="http://strimoid.pl" rel="nofollow">http://strimoid.pl</a></td>
    </tr>
    <tr>
        <td>[test](http://strimoid.pl)</td>
        <td><a href="http://strimoid.pl" rel="nofollow">test</a></td>
    </tr>
    <tr>
        <td>`test`</td>
        <td><code>test</code></td>
    </tr>
    <tr>
        <td>&gt; test</td>
        <td><blockquote>test</blockquote></td>
    </tr>
    <tr>
        <td>! test</td>
        <td>
            <a class="show_spoiler">Pokaż ukrytą treść</a>
            <span class="spoiler">
                test
            </span>
        </td>
    </tr>
    <tr>
        <td>`test`</td>
        <td><code>test</code></td>
    </tr>
    </tbody>
</table>
@stop

@section('sidebar')

@stop
