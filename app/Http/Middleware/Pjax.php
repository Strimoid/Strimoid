<?php

namespace Strimoid\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\DomCrawler\Crawler;

class Pjax
{
    public function handle(Request $request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        if ($response->isRedirection() || !$request->hasHeader('X-PJAX')) {
            return $response;
        }

        $crawler = new Crawler($response->getContent());
        $selector = $request->header('X-PJAX-Container');

        $responseTitle = $crawler->filter('head > title');
        $responseContainer = $crawler->filter($selector);

        if ($responseContainer->count() != 0) {
            $title = '';

            if ($responseTitle->count() != 0) {
                $title = '<title>' . $responseTitle->html() . '</title>';
            }

            $response->setContent($title . $responseContainer->html());
        }

        $response->header('X-PJAX-URL', $request->getRequestUri());

        return $response;
    }
}
