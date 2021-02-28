<?php

namespace Strimoid\Settings\Services;

class SettingsService
{
    protected array $settings = [];
    public function __construct(private \Illuminate\Contracts\Auth\Guard $guard)
    {
    }

    public function add(string $key, string $type, array $options = []): void
    {
        $this->settings[$key] = $options;
        $this->settings[$key]['type'] = $type;
    }

    public function get(string $key)
    {
        if ($this->guard->guest()) {
            return data_get($this->settings[$key], 'default');
        }

        $value = user()->settings()->userCache('settings')->where('key', $key)->first();

        return $value ? $value->value : $this->settings[$key]['default'];
    }

    public function set(string $key, $value): void
    {
        user()->settings()->updateOrCreate(compact('key'), compact('value'));
    }

    public function getOptions($key)
    {
        if ($this->settings[$key]['type'] == 'checkbox') {
            return [true, false];
        }

        $options = data_get($this->settings[$key], 'options', []);

        return value($options);
    }

    public function getSettings()
    {
        return $this->settings;
    }
}
