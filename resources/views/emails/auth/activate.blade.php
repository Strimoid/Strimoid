<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Aktywacja konta</h2>

<div>
    <p>Witaj {!! $user->_id !!}!</p>
    <p>Aby zakończyć proces rejestracji i aktywować konto, wystarczy, że klikniesz na poniższy link:</p>
    <p>
        <a href="{!! URL::to('account/activate', array($user->activation_token)) !!}">{!! URL::to('account/activate', array($user->activation_token)) !!}</a>
    </p>
</div>

<div itemscope itemtype="http://schema.org/EmailMessage">
    <meta itemprop="description" content="Aktywuj konto w serwisie Strimoid.pl"/>
    <div itemprop="action" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="url" href="{!! URL::to('account/activate', array($user->activation_token)) !!}"/>
        <meta itemprop="name" content="Aktywuj konto"/>
    </div>
</div>

</body>
</html>
