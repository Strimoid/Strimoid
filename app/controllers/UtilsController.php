<?php

use Guzzle\Http\Client;

class UtilsController extends BaseController {

    public function getURLTitle()
    {
        $url = Input::get('url');

        try
        {
            $client = new Client($url);
            $response = $client->get()->send();
        }
        catch (Exception $e)
        {
            return Response::json(array('status' => 'error'));
        }

        $contentType = $response->getHeader('Content-Type');

        if (strpos($contentType, 'text/html') === false)
            return Response::json(array('status' => 'error'));

        $html = $response->getBody();

        // Fix for HTML5
        $html = preg_replace( '/<meta charset="(.+)">/',
            '<meta http-equiv="Content-Type" content="text/html; charset=$1">', $html);

        $contentType = $response->getHeader('Content-Type');

        // Small hack to use encoding used on the given page
        $encodingHint = '';

        if (preg_match('/charset=(.*)/', $contentType, $results))
            $encodingHint = '<meta http-equiv="Content-Type" content="text/html; charset='. $results[1] .'">';

        $doc = new DOMDocument('1.0', 'UTF-8');
        @$doc->loadHTML($encodingHint . $html);
        $xpath = new DOMXPath($doc);

        $title = '';
        $description = '';

        // Try to get OpenGraph data first
        try {
            $title = $xpath->query('/html/head/meta[@property="og:title"]')
                ->item(0)->attributes->getNamedItem('content')->nodeValue;
        } catch(Exception $e) {}

        try {
            $description = $xpath->query('/html/head/meta[@property="og:description"]')
                ->item(0)->attributes->getNamedItem('content')->nodeValue;
        } catch(Exception $e) {}

        // Use title and meta data if OpenGraph data wasn't found
        try {
            if (empty($title))
                $title = $xpath->query('/html/head/title')->item(0)->nodeValue;
        } catch(Exception $e) {}

        try {
            if (empty($description))
                $description = $xpath->query('/html/head/meta[@name="description"]')
                    ->item(0)->attributes->getNamedItem('content')->nodeValue;
        } catch(Exception $e) {}

        $title = Str::limit($title, 128);
        $description = Str::limit($description, 255);

        // Find duplicates
        $duplicates = Content::where('url', $url)
            ->where('group_id', Input::get('group'))
            ->get(['title', 'group_id'])
            ->toArray();

        return Response::json(['status' => 'ok', 'title' => $title,
            'description' => $description, 'duplicates' => $duplicates]);
    }

}