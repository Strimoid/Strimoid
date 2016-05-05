<?php namespace Strimoid\Http\Controllers\Content;

use Strimoid\Http\Controllers\BaseController;
use Strimoid\Models\Content;

class ThumbnailController extends BaseController
{
    /**
     * @param Content $content
     *
     * @return \Illuminate\View\View
     */
    public function chooseThumbnail($content)
    {
        if (!$content->canEdit(user())) {
            return redirect()->route('content_comments', $content->getKey())
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        try {
            $thumbnails = \OEmbed::getThumbnails($content->url);
        } catch (\Exception $e) {
            $thumbnails = [];
        }

        $thumbnails[] = 'http://img.bitpixels.com/getthumbnail?code=74491&size=200&url='.urlencode($content->url);

        session(compact('thumbnails'));

        return view('content.thumbnails', compact('content', 'thumbnails'));
    }

    /**
     * @return mixed
     */
    public function saveThumbnail()
    {
        $id = hashids_decode(request('id'));
        $content = Content::findOrFail($id);

        $thumbnails = session('thumbnails', []);

        if (!$content->canEdit(user())) {
            return redirect()->route('content_comments', $content->getKey())
                ->with('danger_msg', 'Minął czas dozwolony na edycję treści.');
        }

        $index = (int) request('thumbnail');

        if (request()->has('thumbnail') && isset($thumbnails[$index])) {
            $content->setThumbnail($thumbnails[$index]);
        } else {
            $content->removeThumbnail();
        }

        return redirect()->route('content_comments', $content);
    }
}
