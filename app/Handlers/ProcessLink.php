<?php namespace Strimoid\Handlers;

use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Symfony\Component\DomCrawler\Crawler;

class ProcessLink
{
    public function fire($job, $data)
    {
        $content = Content::findOrFail($data['id']);

        $url = Config::get('app.iframely_host').'/oembed';
        $response = Guzzle::get($url, [
            'query' => ['url' => $content->url],
        ])->json();

        $content->type = $response['type'];
        $content->save();

        if ($data['thumbnail'] && array_key_exists('thumbnail_url', $response)) {
            $content->setThumbnail($response['thumbnail_url']);
        }

        $content->autoThumbnail();

        WS::send(json_encode([
            'topic' => 'content.'.$content->_id.'.thumbnail',
            'url'   => $content->getThumbnailPath(100, 75),
        ]));

        $content->unset('thumbnail_loading');

        $job->delete();
    }

    private function findThumbnail($url)
    {
        $client = new Client();
        $response = $client->get($url);
        $crawler = new Crawler($response->getBody());

        $requests = $crawler->filter('img')->each(function (Crawler $node, $i) use ($client, $url) {
            $src = $node->attr('src');

            if (starts_with($src, '//')) {
                $src = 'http:'.$src;
            }

            if (!starts_with($src, ['http://', 'https://'])) {
                $src = parse_url($url, PHP_URL_HOST).$src;
            }

            return $client->createRequest('GET', $src);
        });

        $results = Pool::batch($client, $requests);

        $images = [];

        foreach ($results->getSuccessful() as $response) {
            list($x, $y) = @getimagesize($response->getBody()->getUri());
            $images[$response->getEffectiveUrl()] = $x * $y;
        }

        return current(array_keys($images, max($images)));
    }
}
