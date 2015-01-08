<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Zmiana adresu email</h2>

<div>
    <p>Witaj {!! $user->name !!}!</p>
    <p>Aby potwierdzić zmianę adresu email, kliknij w poniższy link:</p>
    <p>
        <a href="{!! URL::to('account/change_email', [$user->email_change_token]) !!}">{!! URL::to('account/change_email', [$user->email_change_token]) !!}</a>
    </p>
</div>

<div itemscope itemtype="http://schema.org/EmailMessage">
    <meta itemprop="description" content="Zmiana adresu email w serwisie Strimoid.pl"/>
    <div itemprop="action" itemscope itemtype="http://schema.org/ViewAction">
        <link itemprop="url" href="{!! URL::to('account/change_email', [$user->email_change_token]) !!}"/>
        <meta itemprop="name" content="Zmień email"/>
    </div>
</div>

</body>
</html>
