<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    "accepted"         => ":attribute musi zostać zaakceptowany.",
    "active_url"       => ":attribute jest nieprawidłowym adresem URL.",
    "after"            => ":attribute musi być datą późniejszą od :date.",
    "alpha"            => ":attribute może zawierać jedynie litery.",
    "alpha_dash"       => ":attribute może zawierać jedynie litery, cyfry i myślniki.",
    "alpha_num"        => ":attribute może zawierać jedynie litery i cyfry.",
    "array"            => "The :attribute must be an array.",
    "before"           => ":attribute musi być datą wcześniejszą od :date.",
    "between"          => [
        "numeric" => ":attribute musi zawierać się w granicach :min - :max.",
        "file"    => ":attribute musi zawierać się w granicach :min - :max kilobajtów.",
        "string"  => ":attribute musi zawierać się w granicach :min - :max znaków.",
        "array"   => "The :attribute must have between :min - :max items.",
    ],
    "confirmed"        => "Potwierdzenie :attribute nie zgadza się.",
    "date"             => ":attribute nie jest prawidłową datą.",
    "date_format"      => ":attribute nie jest w formacie :format.",
    "different"        => ":attribute oraz :other muszą się różnić.",
    "digits"           => ":attribute musi składać się z :digits cyfr.",
    "digits_between"   => ":attribute musi mieć od :min do :max cyfr.",
    "email"            => "Format :attribute jest nieprawidłowy.",
    "exists"           => "Zaznaczony :attribute jest nieprawidłowy.",
    "exists_ci"        => ":attribute nie istnieje.",
    "image"            => ":attribute musi być obrazkiem.",
    "in"               => "Zaznaczony :attribute jest nieprawidłowy.",
    "integer"          => ":attribute musi być liczbą całkowitą.",
    "ip"               => ":attribute musi być prawidłowym adresem IP.",
    "max"              => [
        "numeric" => ":attribute nie może być większy niż :max.",
        "file"    => ":attribute nie może być większy niż :max kilobajtów.",
        "string"  => ":attribute nie może być dłuższy niż :max znaków.",
        "array"   => "The :attribute may not have more than :max items.",
    ],
    "mimes"            => ":attribute musi być plikiem typu :values.",
    "min"              => [
        "numeric" => ":attribute musi być nie mniejszy od :min.",
        "file"    => ":attribute musi mieć przynajmniej :min kilobajtów.",
        "string"  => ":attribute musi mieć przynajmniej :min znaków.",
        "array"   => "The :attribute must have at least :min items.",
    ],
    "not_in"           => "Zaznaczony :attribute is jest nieprawidłowy.",
    "numeric"          => ":attribute musi byc liczbą.",
    "real_email"       => "Podany adres email jest nieprawidłowy.",
    "regex"            => "Format :attribute jest nieprawidłowy.",
    "required"         => "Pole :attribute jest wymagane.",
    "required_if"      => "Pole :attribute jest wymagane gdy :other jest :value.",
    "required_with"    => "Pole :attribute jest wymagane gdy :values jest obecny.",
    "required_without" => "Pole :attribute jest wymagane gdy :values nie jest obecny.",
    "same"             => "Pole :attribute i :other muszą się zgadzać.",
    "size"             => [
        "numeric" => ":attribute musi mieć :size.",
        "file"    => ":attribute musi mieć :size kilobajtów.",
        "string"  => ":attribute musi mieć :size znaków.",
        "array"   => "The :attribute must contain :size items.",
    ],
    "user_password"    => "Podane hasło jest nieprawidłowe.",
    "unique"           => "Taki :attribute już występuje.",
    "unique_ci"        => "Wybrana nazwa jest już zajęta.",
    "unique_email"     => "Podany adres email został już wykorzystany do rejestracji.",
    "url"              => "Format :attribute jest nieprawidłowy.",
    "safe_url"         => "Format :attribute jest nieprawidłowy.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'urlname' => [
            'regex' => 'Adres może zawierać wyłącznie znaki alfanumeryczne oraz znak podkreślenia.',
        ],
        'username' => [
            'regex' => 'Nazwa użytkownika może zawierać wyłącznie znaki alfanumeryczne oraz znak podkreślenia.',
        ],
        'groupname' => [
            'exists_ci' => 'Podana grupa nie istnieje.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'username'    => 'Nazwa użytkownika',
        'password'    => 'Hasło',
        'email'       => 'Adres email',
        'urlname'     => 'Adres grupy',
        'groupname'   => 'Nazwa grupy',
        'description' => 'Opis',
        'text'        => 'Treść',
        'reason'      => 'Powód',
        'location'    => 'Miejscowość',
        'age'         => 'Rok urodzenia',
        'title'       => 'Tytuł',
        'name'        => 'Nazwa',
    ],

];
