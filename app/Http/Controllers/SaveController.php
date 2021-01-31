<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;

class SaveController extends BaseController
{
    public function saveContent(Request $request)
    {
        $id = hashids_decode($request->get('content'));
        $content = Content::findOrFail($id);

        $content->saves()->create([
            'user_id' => auth()->id(),
        ]);

        return Response::json(['status' => 'ok']);
    }

    public function removeContent(Request $request)
    {
        $id = hashids_decode($request->get('content'));
        $content = Content::findOrFail($id);

        $content->usave()->delete();

        return Response::json(['status' => 'ok']);
    }

    public function saveEntry(Request $request)
    {
        $id = hashids_decode($request->get('entry'));
        $entry = Entry::findOrFail($id);

        $entry->saves()->create([
            'user_id' => auth()->id(),
        ]);

        return Response::json(['status' => 'ok']);
    }

    public function removeEntry(Request $request)
    {
        $id = hashids_decode($request->get('entry'));
        $entry = Entry::findOrFail($id);

        $entry->usave()->delete();

        return Response::json(['status' => 'ok']);
    }
}
