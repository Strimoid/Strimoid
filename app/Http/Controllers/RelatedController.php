<?php namespace Strimoid\Http\Controllers;

use Auth, Input, Redirect, Response, Validator;
use Strimoid\Models\ContentRelated;

class RelatedController extends BaseController {

    public function addRelated($content)
    {
        $validator = ContentRelated::validate(Input::all());

        if ($validator->fails())
        {
            return Redirect::route('content_comments', $content->_id)->withInput()->withErrors($validator);
        }

        if (Auth::user()->isBanned($content->group))
        {
            return Redirect::route('content_comments', $content->_id)
                ->withInput()
                ->with('danger_msg', 'Zostałeś zbanowany w wybranej grupie');
        }

        if ($content->group->type == Group::TYPE_ANNOUNCEMENTS && !Auth::user()->isModerator($content->group))
        {
            return Redirect::route('content_comments', $content->_id)
                ->withInput()
                ->with('danger_msg', 'Nie możesz dodawać powiązanych w tej grupie');
        }

        $related = new ContentRelated(Input::only([
            'title', 'url', 'nsfw', 'eng'
        ]));

        if (Input::get('thumbnail') == 'on') {
            $url = OEmbed::getThumbnail($this->url);
            if ($url) $related->setThumbnail($url);
        }

        $related->user()->associate(Auth::user());
        $related->content()->associate($content);

        $content->increment('related_count');

        $related->save();

        return Redirect::route('content_comments', $content->_id);
    }

    public function removeRelated($related = null)
    {
        $related = ($related instanceof ContentRelated)
            ?: ContentRelated::findOrFail(Input::get('id'));

        if (Auth::id() == $related->user->getKey())
        {
            $related->delete();
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

    public function store($content)
    {
        $validator = Validator::make(Input::all(), [
            'title' => 'required|min:1|max:128',
            'url' => 'required|url_custom',
        ]);

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        if (Auth::user()->isBanned($content->group))
        {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.']);
        }

        $related = new ContentRelated(Input::only([
            'title', 'url', 'nsfw', 'eng'
        ]));

        if (Input::get('thumbnail') != 'false' && Input::get('thumbnail') != 'off') {
            $url = OEmbed::getThumbnail($this->url);
            if ($url) $related->setThumbnail($url);
        }

        $related->user()->associate(Auth::user());
        $related->content()->associate($content);

        $content->increment('related_count');

        $related->save();

        return Response::json(['status' => 'ok', '_id' => $related->_id, 'related' => $related]);
    }

}