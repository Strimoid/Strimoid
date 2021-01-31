<?php

namespace Strimoid\Http\Controllers\Content;

use Illuminate\Support\Facades\Gate;
use Strimoid\Facades\OEmbed;
use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\Content;

class ThumbnailController extends BaseController
{
    public function chooseThumbnail(Content $content)
    {
        $policyDecision = Gate::inspect('edit', $content);

        if ($policyDecision->denied()) {
            return redirect()
                ->route('content_comments', $content->getKey())
                ->with('danger_msg', $policyDecision->message());
        }

        try {
            $thumbnails = OEmbed::getThumbnails($content->url);
        } catch (\Exception $exception) {
            logger()->error($exception);
            $thumbnails = [];
        }

        $thumbnails[] = 'https://img.bitpixels.com/getthumbnail?code=74491&size=200&url=' . urlencode($content->url);

        session(compact('thumbnails'));

        return view('content.thumbnails', compact('content', 'thumbnails'));
    }

    public function saveThumbnail()
    {
        $id = hashids_decode(request('id'));
        $content = Content::findOrFail($id);

        $policyDecision = Gate::inspect('edit', $content);

        if ($policyDecision->denied()) {
            return redirect()
                ->route('content_comments', $content->getKey())
                ->with('danger_msg', $policyDecision->message());
        }

        $thumbnails = session('thumbnails', []);

        $index = (int) request('thumbnail');

        if (request()->has('thumbnail') && isset($thumbnails[$index])) {
            $content->setThumbnail($thumbnails[$index]);
        } else {
            $content->removeThumbnail();
        }

        return redirect()->route('content_comments', $content);
    }
}
