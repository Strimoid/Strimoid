<?php namespace Strimoid\Services;

use DateTimeZone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class Settings
{
    protected $settings = [];

    public function __construct()
    {
        $this->add('homepage_subscribed', [
            'type'    => 'checkbox',
            'default' => false,
            'options' => [true, false],
        ]);

        $this->add('contents_per_page', [
            'type'    => 'select',
            'default' => 25,
            'options' => [
                10 => 10, 20 => 20, 25 => 25, 50 => 50, 100 => 100,
            ],
        ]);

        $this->add('entries_per_page', [
            'type'    => 'select',
            'default' => 25,
            'options' => [
                10 => 10, 20 => 20, 25 => 25, 50 => 50, 100 => 100,
            ],
        ]);

        $this->add('timezone', [
            'type'    => 'select',
            'default' => 'Europe/Warsaw',
            'options' => function () {
                return $this->getTimezones();
            },
        ]);

        $this->add('notifications.auto_read', [
            'type'    => 'checkbox',
            'default' => false,
            'options' => [true, false],
        ]);
    }

    public function getTimezones()
    {
        $timezones = [];

        foreach (DateTimeZone::listIdentifiers() as $timezone) {
            $key = 'timezones.'.Str::lower($timezone);

            if ($translation = Lang::has($key)) {
                $timezones[$timezone] = Lang::get($key);
            }
        }

        return $timezones;
    }

    public function add($key, $options)
    {
        $this->settings[$key] = $options;
    }

    public function set($key, $value)
    {
        Auth::user()->settings()->where('key', $key)->updateOrCreate([
            'key'   => $key,
            'value' => $value,
        ]);
    }

    public function get($key)
    {
        if (Auth::guest()) return $this->settings[$key]['default'];

        $value = Auth::user()->settings()->where('key', $key)->first();
        return $value ?: $this->settings[$key]['default'];
    }

    public function getOptions($key)
    {
        return value($this->settings[$key]['options']);
    }
}
