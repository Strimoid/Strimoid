<?php

use Illuminate\Support\Str;
use Strimoid\Settings\Facades\Setting;

$timezones = static fn () => collect(\DateTimeZone::listIdentifiers())->mapWithKeys(function ($timezone) {
    $key = 'timezones.' . Str::lower($timezone);

    return [$timezone => trans($key)];
});

Setting::add('enter_send', 'checkbox', [
    'default' => 'false',
]);

Setting::add('homepage_subscribed', 'checkbox', [
    'default' => false,
]);

Setting::add('contents_per_page', 'select', [
    'default' => 25,
    'options' => [
        10 => 10, 20 => 20, 25 => 25, 50 => 50, 100 => 100,
    ],
]);

Setting::add('entries_per_page', 'select', [
    'default' => 25,
    'options' => [
        10 => 10, 20 => 20, 25 => 25, 50 => 50, 100 => 100,
    ],
]);

Setting::add('language', 'select', [
    'default' => 'auto',
    'options' => [
        'auto' => 'Wykryj automatycznie',
        'pl' => 'Polski',
        'en' => 'English',
    ]
]);

Setting::add('timezone', 'select', [
    'default' => 'Europe/Warsaw',
    'options' => $timezones,
]);

Setting::add('notifications.auto_read', 'checkbox', [
    'default' => false,
]);

Setting::add('notifications_sound', 'checkbox', [
    'default' => false,
]);

Setting::add('pin_navbar', 'checkbox', [
    'default' => false,
]);

Setting::add('disable_groupstyles', 'checkbox', [
    'default' => false,
]);

Setting::add('css_style', 'text', [
    'default' => '',
]);
