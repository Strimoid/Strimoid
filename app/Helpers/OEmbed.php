<?php

namespace Strimoid\Helpers;

use Illuminate\Support\Facades\Cache;
use Strimoid\Facades\Guzzle;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OEmbed
{
    protected array $mimetypes = [
        'audio/' => 'embedAudio',
        'image/' => 'embedImage',
        'video/' => 'embedVideo',
    ];

    public function getThumbnail(string $url): ?string
    {
        try {
            $data = $this->getData($url);

            $images = $this->extractImages($data['links'] ?? []);
            $thumbnail = Arr::first($images);

            return data_get($thumbnail, 'href');
        } catch (RequestException $e) {
            return null;
        }
    }

    public function getThumbnails(string $url): array
    {
        $data = $this->getData($url);
        $images = $this->extractImages($data['links'] ?? []);

        return Arr::pluck($images, 'href');
    }

    public function getData(string $url): array
    {
        $query = array_filter([
            'url' => $url,
            'api_key' => config('strimoid.oembed.api_key'),
        ]);

        /** @var Response $response */
        $response = Guzzle::get($this->endpoint(), compact('query'));

        return json_decode($response->getBody(), true);
    }

    protected function extractImages(array $links): array
    {
        $thumbnails = $links['thumbnail'] ?? [];
        $files = $links['file'] ?? [];

        $sources = array_merge($thumbnails, $files);

        return array_filter($sources, fn ($value) => $this->isImage($value));
    }

    protected function isImage(array $link)
    {
        $rel = data_get($link, 'rel', []);
        $type = data_get($link, 'type');

        if (in_array('thumbnail', $rel)) {
            return true;
        }

        if (in_array('file', $rel) && Str::startsWith($type, 'image')) {
            return true;
        }

        return false;
    }

    public function getEmbedHtml(string $url, bool $autoPlay = true)
    {
        $key = md5($url);

        if (!$autoPlay) {
            $key .= '.no-ap';
        }

        return Cache::rememberForever($key, fn () => $this->fetchJson($url, $autoPlay));
    }

    protected function fetchJson(string $url, bool $autoPlay): ?string
    {
        try {
            $query = array_filter([
                'ssl' => 'true',
                'url' => $url,
                'api_key' => config('strimoid.oembed.api_key'),
            ]);

            if ($autoPlay) {
                $query['autoplay'] = 'true';
            }

            $data = (string) Guzzle::get($this->endpoint(), compact('query'))->getBody();
            $data = \GuzzleHttp\json_decode($data, true);

            return $this->processData($data);
        } catch (RequestException $e) {
            return null;
        }
    }

    protected function processData($data): ?string
    {
        if (array_key_exists('html', $data)) {
            return $data['html'];
        }

        $links = $data['links'] ?? [];

        foreach ($links as $link) {
            $rel = data_get($link, 'rel', []);

            if (in_array('file', $rel)) {
                return $this->embedMedia($link);
            }

            if (in_array('image', $rel)) {
                return $this->embedImage($link['href']);
            }
        }

        return null;
    }

    protected function embedMedia($link)
    {
        foreach ($this->mimetypes as $mimetype => $function) {
            if (Str::startsWith($link['type'], $mimetype)) {
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

    protected function endpoint(): string
    {
        $baseUrl = config('strimoid.oembed.url');

        return $baseUrl . '/iframely';
    }
}
