<?php

namespace Strimoid\Http\Controllers\Content;

use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Log;
use Strimoid\Helpers\OEmbed;
use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\Content;

class ThumbnailController extends BaseController
{
    public function __construct(private OEmbed $oembed, private Gate $gate, private Redirector $redirector, private SessionManager $sessionManager, private Factory $viewFactory)
    {
    }

    public function chooseThumbnail(Content $content)
    {
        $policyDecision = $this->gate->inspect('edit', $content);

        if ($policyDecision->denied()) {
            return $this->redirector
                ->route('content_comments', $content->getKey())
                ->with('danger_msg', $policyDecision->message());
        }

        $thumbnails = [];

        try {
            $thumbnails = $this->oembed->getThumbnails($content->url);
        } catch (\Exception $exception) {
            Log::warning($exception);
        }

        $thumbnails[] = 'https://img.bitpixels.com/getthumbnail?code=74491&size=200&url=' . urlencode($content->url);

        $this->sessionManager->put(compact('thumbnails'));

        return $this->viewFactory->make('content.thumbnails', compact('content', 'thumbnails'));
    }

    public function saveThumbnail(Request $request)
    {
        $id = hashids_decode($request->input('id'));
        $content = Content::findOrFail($id);

        $policyDecision = $this->gate->inspect('edit', $content);

        if ($policyDecision->denied()) {
            return $this->redirector
                ->route('content_comments', $content->getKey())
                ->with('danger_msg', $policyDecision->message());
        }

        $thumbnails = $this->sessionManager->get('thumbnails', []);

        $index = (int) $request->input('thumbnail');

        if ($request->has('thumbnail') && isset($thumbnails[$index])) {
            $content->setThumbnail($thumbnails[$index]);
        } else {
            $content->removeThumbnail();
        }

        return $this->redirector->route('content_comments', $content);
    }
}
