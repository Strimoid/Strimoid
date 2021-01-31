<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Strimoid\Facades\OEmbed;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Strimoid\Models\ContentRelated;

class RelatedController extends BaseController
{
    public function addRelated(Request $request, $content)
    {
        $this->validate($request, ContentRelated::validationRules());

        if (Auth::user()->isBanned($content->group)) {
            return Redirect::route('content_comments', $content->getKey())
                ->withInput()
                ->with('danger_msg', 'Zostałeś zbanowany w wybranej grupie');
        }

        if ($content->group->type === 'announcements'
            && !Auth::user()->isModerator($content->group)) {
            return Redirect::route('content_comments', $content->getKey())
                ->withInput()
                ->with('danger_msg', 'Nie możesz dodawać powiązanych w tej grupie');
        }

        $related = new ContentRelated($request->only([
            'title', 'url', 'nsfw', 'eng',
        ]));
        $related->user()->associate(Auth::user());
        $related->content()->associate($content);
        if ($request->get('thumbnail') === 'on') {
            $url = OEmbed::getThumbnail($related->url);
            if ($url) {
                $related->setThumbnail($url);
            }
        }

        $related->save();

        return Redirect::route('content_comments', $content->hashid);
    }

    public function removeRelated(Request $request, $related = null)
    {
        $related = $related instanceof ContentRelated
            ?: ContentRelated::findOrFail(hashids_decode($request->get('id')));

        if (Auth::id() === $related->user->getKey()) {
            $related->delete();

            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

    public function store(Request $request, $content)
    {
        $this->validate($request, ContentRelated::validationRules());

        if (Auth::user()->isBanned($content->group)) {
            return Response::json([
                'status' => 'error',
                'error' => 'Użytkownik został zbanowany w wybranej grupie.',
            ]);
        }

        $related = new ContentRelated($request->only([
            'title', 'url', 'nsfw', 'eng',
        ]));

        if ($request->get('thumbnail') !== 'false' && $request->get('thumbnail') !== 'off') {
            $url = OEmbed::getThumbnail($related->url);
            if ($url) {
                $related->setThumbnail($url);
            }
        }

        $related->user()->associate(Auth::user());
        $related->content()->associate($content);

        $related->save();

        return Response::json([
            'status' => 'ok',
            '_id' => $related->hashId(),
            'related' => $related,
        ]);
    }
}
