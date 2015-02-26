<?php namespace Strimoid\Http\Controllers;

use Auth;
use Input;
use Response;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Save;

class SaveController extends BaseController
{
    public function saveContent()
    {
        $content = Content::findOrFail(Input::get('content'));

        if ($this->findUserSave($content, Auth::id())) {
            return;
        }

        $save = new Save(['user_id' => Auth::id()]);
        $content->saves()->save($save);

        return Response::json(['status' => 'ok']);
    }

    public function removeContent()
    {
        $content = Content::findOrFail(Input::get('content'));

        $save = $this->findUserSave($content, Auth::id());
        $save->delete();

        return Response::json(['status' => 'ok']);
    }

    public function saveEntry()
    {
        $entry = Entry::findOrFail(Input::get('entry'));

        if ($this->findUserSave($entry, Auth::id())) {
            return;
        }

        $save = new Save(['user_id' => Auth::id()]);
        $entry->saves()->save($save);

        return Response::json(['status' => 'ok']);
    }

    public function removeEntry()
    {
        $entry = Entry::findOrFail(Input::get('entry'));

        $save = $this->findUserSave($entry, Auth::id());
        $save->delete();

        return Response::json(['status' => 'ok']);
    }

    private function findUserSave($object, $id)
    {
        $save = $object->saves()
            ->where('user_id', $id)
            ->first();

        return $save;
    }
}
