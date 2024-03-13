<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Strimoid\Facades\OEmbed;
use Strimoid\Models\ContentRelated;

class RelatedController extends BaseController
{
    public function __construct(private readonly \Illuminate\Auth\AuthManager $authManager, private readonly \Illuminate\Routing\Redirector $redirector, private readonly \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }
    public function addRelated(Request $request, $content)
    {
        $this->validate($request, ContentRelated::validationRules());

        if ($this->authManager->user()->isBanned($content->group)) {
            return $this->redirector->route('content_comments', $content->getKey())
                ->withInput()
                ->with('danger_msg', 'Zostałeś zbanowany w wybranej grupie');
        }

        if ($content->group->type === 'announcements'
            && !$this->authManager->user()->isModerator($content->group)) {
            return $this->redirector->route('content_comments', $content->getKey())
                ->withInput()
                ->with('danger_msg', 'Nie możesz dodawać powiązanych w tej grupie');
        }

        $related = new ContentRelated($request->only([
            'title', 'url', 'nsfw', 'eng',
        ]));
        $related->user()->associate($this->authManager->user());
        $related->content()->associate($content);
        if ($request->get('thumbnail') === 'on') {
            $url = OEmbed::getThumbnail($related->url);
            if ($url) {
                $related->setThumbnail($url);
            }
        }

        $related->save();

        return $this->redirector->route('content_comments', $content->hashid);
    }

    public function removeRelated(Request $request, $related = null)
    {
        $related = $related instanceof ContentRelated
            ?: ContentRelated::findOrFail(hashids_decode($request->get('id')));

        if ($this->authManager->id() === $related->user->getKey()) {
            $related->delete();

            return $this->responseFactory->json(['status' => 'ok']);
        }

        return $this->responseFactory->json(['status' => 'error']);
    }

    public function store(Request $request, $content)
    {
        $this->validate($request, ContentRelated::validationRules());

        if ($this->authManager->user()->isBanned($content->group)) {
            return $this->responseFactory->json([
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

        $related->user()->associate($this->authManager->user());
        $related->content()->associate($content);

        $related->save();

        return $this->responseFactory->json([
            'status' => 'ok',
            '_id' => $related->hashId(),
            'related' => $related,
        ]);
    }
}
