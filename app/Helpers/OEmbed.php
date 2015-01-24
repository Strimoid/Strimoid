<?php namespace Strimoid\Helpers; 

use Cache, Config, Guzzle;
use GuzzleHttp\Exception\RequestException;

class OEmbed {

    public function getHtml($url)
    {
        $key = md5($url);

        $html = Cache::driver('oembed')
            ->rememberForever($key, function() use($url)
            {
                return $this->fetchHtml($url);
            });

        return $html;
    }

    private function fetchHtml($url)
    {
        $host = Config::get('strimoid.oembed');
        $endpoint = $host .'/oembed';
        $query = ['url' => $url];

        try {
            $response = Guzzle::get($endpoint, compact('query'));
            $json = $response->json();

            if (!array_key_exists('html', $json)) return false;

            return $json['html'];
        } catch(RequestException $e) {}

        return false;
    }

}
