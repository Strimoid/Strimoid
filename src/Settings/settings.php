<?php

$timezones = function () {
    return collect(\DateTimeZone::listIdentifiers())->map(function ($timezone) {
        $key = 'timezones.'.Str::lower($timezone);
        return [$timezone => trans($key)];
    })->flatten(1);
};

Setting::add('homepage_subscribed', 'checkbox', [
    'default' => false,
    'options' => [true, false],
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

Setting::add('timezone', 'select', [
    'default' => 'Europe/Warsaw',
    'options' => $timezones,
]);

Setting::add('notifications.auto_read', 'checkbox', [
    'default' => false,
    'options' => [true, false],
]);
