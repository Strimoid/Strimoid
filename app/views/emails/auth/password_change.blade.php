<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Zmiana hasła</h2>

<div>
    <p>Witaj {{ $user->_id }}!</p>
    <p>Hasło do twojego konta zostało zmienione.</p>
    <p>Jeśli zmiana ta nie została dokonana przez Ciebie, prosimy o niezwłoczny kontakt z administratorem serwisu: {{ URL::to('contact') }}</p>
</div>
</body>
</html>
