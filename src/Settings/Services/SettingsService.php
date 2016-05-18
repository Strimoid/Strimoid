<?php namespace Strimoid\Settings\Services;

class SettingsService
{
    protected $settings = [];

    public function add(string $key, string $type, array $options = [])
    {
        $this->settings[$key] = $options;
        $this->settings[$key]['type'] = $type;
    }

    public function get(string $key)
    {
        if (auth()->guest()) {
            return data_get($this->settings[$key], 'default');
        }

        $value = user()->settings()->where('key', $key)->first();
        return $value ? $value->value : $this->settings[$key]['default'];
    }

    public function set(string $key, $value)
    {
        user()->settings()->updateOrCreate(compact('key'), compact('value'));
    }

    public function getOptions($key)
    {
        $options = data_get($this->settings[$key], 'options', []);
        return value($options);
    }

    public function getSettings()
    {
        return $this->settings;
    }
}
