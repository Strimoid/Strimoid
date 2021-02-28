<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;

class SaveController extends BaseController
{
    public function __construct(private \Illuminate\Contracts\Auth\Guard $guard, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }
    public function saveContent(Request $request)
    {
        $id = hashids_decode($request->get('content'));
        $content = Content::findOrFail($id);

        $content->saves()->create([
            'user_id' => $this->guard->id(),
        ]);

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function removeContent(Request $request)
    {
        $id = hashids_decode($request->get('content'));
        $content = Content::findOrFail($id);

        $content->userSave()->delete();

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function saveEntry(Request $request)
    {
        $id = hashids_decode($request->get('entry'));
        $entry = Entry::findOrFail($id);

        $entry->saves()->create([
            'user_id' => $this->guard->id(),
        ]);

        return $this->responseFactory->json(['status' => 'ok']);
    }

    public function removeEntry(Request $request)
    {
        $id = hashids_decode($request->get('entry'));
        $entry = Entry::findOrFail($id);

        $entry->userSave()->delete();

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
