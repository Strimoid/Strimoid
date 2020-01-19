<?php

namespace Strimoid\Helpers;

use Cache;
use Config;
use Guzzle;
use GuzzleHttp\Exception\RequestException;

class OEmbed
{
    protected array $mimetypes = [
        'audio/' => 'embedAudio',
        'image/' => 'embedImage',
        'video/' => 'embedVideo',
    ];

    public function getThumbnail(string $url)
    {
        try {
            $data = $this->getData($url);

            $image = array_first($data['links']['thumbnail'], fn ($key, $value) => $this->isImage($value));

            return data_get($image, 'href', null);
        } catch (RequestException $e) {
        }
    }

    public function getThumbnails(string $url)
    {
        $data = $this->getData($url);
        $data = $data['links']['thumbnail'];
        return array_pluck($data, 'href');
    }

    public function getData(string $url)
    {
        $query = ['url' => $url];

        return Guzzle::get($this->endpoint(), compact('query'))->json();
    }

    protected function isImage($link)
    {
        $rel = data_get($link, 'rel', []);
        $type = data_get($link, 'type');

        if (in_array('thumbnail', $rel)) {
            return true;
        }

        if (in_array('file', $rel) && starts_with($type, 'image')) {
            return true;
        }

        return false;
    }

    public function getEmbedHtml($url, $autoPlay = true)
    {
        $key = md5($url);

        if (!$autoPlay) {
            $key .= '.no-ap';
        }

        return Cache::driver('oembed')
            ->rememberForever($key, fn () => $this->fetchJson($url, $autoPlay));
    }

    /**
     * @param $url
     *
     * @return bool|string
     */
    protected function fetchJson($url, bool $autoPlay)
    {
        try {
            $query = ['ssl' => 'true', 'url' => $url];

            if ($autoPlay) {
                $query['autoplay'] = 'true';
            }

            $data = (string) Guzzle::get($this->endpoint(), compact('query'))->getBody();
            $data = \GuzzleHttp\json_decode($data, true);

            return $this->processData($data);
        } catch (RequestException $e) {
        }

        return false;
    }

    protected function processData($data)
    {
        if (array_key_exists('html', $data)) {
            return $data['html'];
        }

        foreach ($data['links'] as $link) {
            $rel = data_get($link, 'rel', []);

            if (in_array('file', $rel)) {
                return $this->embedMedia($link);
            }

            if (in_array('image', $rel)) {
                return $this->embedImage($link['href']);
            }
        }

        return false;
    }

    protected function embedMedia($link)
    {
        foreach ($this->mimetypes as $mimetype => $function) {
            if (starts_with($link['type'], $mimetype)) {
                return $this->{$function}($link['href']);
            }
        }

        return false;
    }

    protected function embedAudio($href)
    {
        return '<audio src="' . $href . '"controls autoplay></audio>';
    }

    protected function embedImage($href)
    {
        return '<img src="' . $href . '">';
    }

    protected function embedVideo($href)
    {
        return '<video src="' . $href . '"controls autoplay></audio>';
    }

    /**
     * Return OEmbed API endpoint URL.
     *
     */
    protected function endpoint(): string
    {
        $host = config('strimoid.oembed');

        return $host . '/iframely';
    }
}
