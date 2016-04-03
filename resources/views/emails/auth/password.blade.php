@extends('emails.master')

@section('title')

@endsection

@section('content')
    <h1>Zmiana hasła</h1>

    <p>Witaj {{ $user->name }},</p>
    <p>Aby zresetować hasło kliknij w poniższy przycisk:</p>

    <!-- button -->
    <table class="btn-primary" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td>
                <a href="{{ URL::to('password/reset', [$token]) }}">Zmień hasło w serwisie Strm.pl</a>
            </td>
        </tr>
    </table>
    <!-- /button -->

    <div itemscope itemtype="http://schema.org/EmailMessage">
        <meta itemprop="description" content="Zmiana hasła w serwisie Strm.pl"/>
        <div itemprop="action" itemscope itemtype="http://schema.org/ViewAction">
            <link itemprop="url" href="{{ URL::to('password/reset', [$token]) }}"/>
            <meta itemprop="name" content="Zmień hasło"/>
        </div>
    </div>
@endsection
