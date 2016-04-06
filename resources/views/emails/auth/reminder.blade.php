<!DOCTYPE html>
<html lang="pl-PL">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Resetowanie hasła</h2>

		<div>
			Aby zresetować hasło wypełnij formularz na podanej stronie: <a href="{!! URL::to('password/reset', [$token]) !!}">{!! URL::to('password/reset', [$token]) !!}</a>
		</div>
	</body>
</html>
