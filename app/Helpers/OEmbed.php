<?php namespace Strimoid\Helpers; 

use Cache, Config, Guzzle;
use GuzzleHttp\Exception\RequestException;

class OEmbed {

    protected $mimetypes = [
        'audio/' => 'embedAudio',
        'image/' => 'embedImage',
        'video/' => 'embedVideo',
    ];

    public function getThumbnail($url)
    {
        try {
            $data = Guzzle::get($this->endpoint(), [
                'url' => $url,
            ])->json();

            return $this->findThumbnail($data);
        } catch(RequestException $e) {}
    }

    protected function findThumbnail($data)
    {
        foreach ($data['links'] as $link)
        {
            if (in_array('thumbnail', $link['rel']))
            {
                return $link['href'];
            }
        }

        return false;
    }

    public function getHtml($url)
    {
        $key = md5($url);

        $html = Cache::driver('oembed')
            ->rememberForever($key, function() use($url) {
                return $this->fetchJson($url);
            });

        return $html;
    }

    protected function fetchJson($url)
    {
        try {
            $data = Guzzle::get($this->endpoint(), [
                'autoplay' => 'true',
                'ssl' => 'true',
                'url' => $url,
            ])->json();

            return $this->processData($data);
        } catch(RequestException $e) {}

        return false;
    }

    protected function processData($data)
    {
        if (array_key_exists('html', $data)) return $data['html'];

        foreach ($data['links'] as $link)
        {
            if (in_array('file', $link['rel']))
            {
                return $this->embedMedia($link);
            }

            if (in_array('image', $link['rel']))
            {
                return $this->embedImage($link['href']);
            }
        }

        return false;
    }

    protected function embedMedia($link)
    {
        foreach ($this->mimetypes as $mimetype => $function)
        {
            if (starts_with($link['type'], $mimetype))
            {
                return $this->{$function}($link['href']);
            }
        }

        return false;
    }

    protected function embedAudio($href)
    {
        return '<audio src="'. $href .'"controls autoplay></audio>';
    }

    protected function embedImage($href)
    {
        return '<img src="'. $href .'">';
    }

    protected function embedVideo($href)
    {
        return '<video src="'. $href .'"controls autoplay></audio>';
    }

    /**
     * Return OEmbed API endpoint URL.
     *
     * @return string
     */
    protected function endpoint()
    {
        $host = Config::get('strimoid.oembed');
        return $host .'/iframely';
    }

}
