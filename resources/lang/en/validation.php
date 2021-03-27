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

    'accepted'         => 'The :attribute must be accepted.',
    'active_url'       => 'The :attribute is not a valid URL.',
    'after'            => 'The :attribute must be a date after :date.',
    'alpha'            => 'The :attribute may only contain letters.',
    'alpha_dash'       => 'The :attribute may only contain letters, numbers, and dashes.',
    'alpha_num'        => 'The :attribute may only contain letters and numbers.',
    'array'            => 'The :attribute must be an array.',
    'before'           => 'The :attribute must be a date before :date.',
    'between'          => [
        'numeric' => 'The :attribute must be between :min - :max.',
        'file'    => 'The :attribute must be between :min - :max kilobytes.',
        'string'  => 'The :attribute must be between :min - :max characters.',
        'array'   => 'The :attribute must have between :min - :max items.',
    ],
    'confirmed'        => 'The :attribute confirmation does not match.',
    'date'             => 'The :attribute is not a valid date.',
    'date_format'      => 'The :attribute does not match the format :format.',
    'different'        => 'The :attribute and :other must be different.',
    'digits'           => 'The :attribute must be :digits digits.',
    'digits_between'   => 'The :attribute must be between :min and :max digits.',
    'email'            => 'The :attribute format is invalid.',
    'exists'           => 'The selected :attribute is invalid.',
    'exists_ci'        => ":attribute doesn't exist.",
    'image'            => 'The :attribute must be an image.',
    'in'               => 'The selected :attribute is invalid.',
    'integer'          => 'The :attribute must be an integer.',
    'ip'               => 'The :attribute must be a valid IP address.',
    'max'              => [
        'numeric' => 'The :attribute may not be greater than :max.',
        'file'    => 'The :attribute may not be greater than :max kilobytes.',
        'string'  => 'The :attribute may not be greater than :max characters.',
        'array'   => 'The :attribute may not have more than :max items.',
    ],
    'mimes'            => 'The :attribute must be a file of type: :values.',
    'min'              => [
        'numeric' => 'The :attribute must be at least :min.',
        'file'    => 'The :attribute must be at least :min kilobytes.',
        'string'  => 'The :attribute must be at least :min characters.',
        'array'   => 'The :attribute must have at least :min items.',
    ],
    'not_in'           => 'The selected :attribute is invalid.',
    'numeric'          => 'The :attribute must be a number.',
    'real_email'       => 'Given e-mail address is not valid.',
    'regex'            => 'The :attribute format is invalid.',
    'required'         => 'The :attribute field is required.',
    'required_if'      => 'The :attribute field is required when :other is :value.',
    'required_with'    => 'The :attribute field is required when :values is present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'same'             => 'The :attribute and :other must match.',
    'size'             => [
        'numeric' => 'The :attribute must be :size.',
        'file'    => 'The :attribute must be :size kilobytes.',
        'string'  => 'The :attribute must be :size characters.',
        'array'   => 'The :attribute must contain :size items.',
    ],
    'user_password'    => 'Given password is not valid.',
    'unique'           => 'The :attribute has already been taken.',
    'unique_ci'        => 'Given username is already taken.',
    'unique_email'     => 'Given e-mail address was already registered.',
    'url'              => 'The :attribute format is invalid.',
    'url_custom'       => 'The :attribute format is invalid.',
    'safe_url'         => ':attribute format is invalid.',

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
            'regex' => 'Address may only contain alphanumeric characters and underscore.',
        ],
        'username' => [
            'regex' => 'Username may only contain alphanumeric characters and underscore.',
        ],
        'groupname' => [
            'exists' => 'Given group does not exist.',
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
        'username'    => 'Username',
        'password'    => 'Password',
        'email'       => 'E-mail address',
        'urlname'     => 'Group address',
        'groupname'   => 'Group name',
        'description' => 'Description',
        'text'        => 'Content',
        'reason'      => 'Reason',
        'location'    => 'City',
        'age'         => 'Year of birth',
        'title'       => 'Title',
        'name'        => 'Name',
    ],

];
