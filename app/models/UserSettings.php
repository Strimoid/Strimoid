<?php namespace Strimoid\Models;

class UserSettings {

    protected $settings = [];

    public function __construct()
    {
        $this->set('homepage_subscribed', [
            'type' => 'checkbox',
            'default' => false,
            'options' => [true, false]
        ]);

        $this->set('contents_per_page', [
            'type' => 'select',
            'default' => 25,
            'options' => [
                10 => 10, 20 => 20, 25 => 25, 50 => 50, 100 => 100
            ]
        ]);

        $this->set('entries_per_page', [
            'type' => 'select',
            'default' => 25,
            'options' => [
                10 => 10, 20 => 20, 25 => 25, 50 => 50, 100 => 100
            ]
        ]);

        $this->set('timezone', [
            'type' => 'select',
            'default' => 'Europe/Warsaw',
            'options' => function() {
                return $this->getTimezones();
            }
        ]);

        $this->set('notifications.auto_read', [
            'type' => 'checkbox',
            'default' => false,
            'options' => [true, false]
        ]);
    }

    public function getTimezones()
    {
        $timezones = array();

        foreach (DateTimeZone::listIdentifiers() as $timezone)
        {
            $key = 'timezones.'. Str::lower($timezone);

            if ($translation = Lang::has($key))
            {
                $timezones[$timezone] = Lang::get($key);
            }

        }

        return $timezones;
    }

    public function set($key, $options)
    {
        $this->settings[$key] = $options;
    }

    public function get($key)
    {
        if (Auth::guest() || !array_get(Auth::user()->settings, $key))
        {
            return $this->settings[$key]['default'];
        }
        else
        {
            return array_get(Auth::user()->settings, $key);
        }
    }

    public function getOptions($key)
    {
        return value($this->settings[$key]['options']);
    }

} 