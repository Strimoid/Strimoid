<?php

class SaveController extends BaseController
{

    public function showContents()
    {
        $page = Paginator::getCurrentPage();
        $ids = Auth::user()->data->_saved_contents;

        if (is_array($ids))
        {
            $total = count($ids);

            $contentIds = array_slice(array_reverse($ids), ($page - 1) * 20, 20);;

            $contents = Content::with('user')->whereIn('_id', $contentIds)->get();

            $contents->sortBy(function($content) use($contentIds) {
                return array_search($content->_id, $contentIds);
            });
        }
        else
        {
            $total = 0;
            $contents = new \Illuminate\Support\Collection();
        }

        $results['contents'] = Paginator::make($contents->all(), $total, Settings::get('contents_per_page'));
        $results['group_name'] = 'saved';

        return view('content.display', $results);
    }

    public function showEntries()
    {
        $page = Paginator::getCurrentPage();
        $ids = (array) Auth::user()->data->_saved_entries;

        $total = count($ids);

        $entryIds = array_slice(array_reverse($ids), ($page - 1) * 20, 20);;

        $entries = Entry::whereIn('_id', $entryIds)
            ->with('user')
            ->with(['replies.user' => function($q) { $q->remember(10); }])
            ->orderBy('created_at', 'desc')
            ->slice('_replies', -2)
            ->get();

        $entries->sortBy(function($entry) use($entryIds) {
            return array_search($entry->_id, $entryIds);
        });

        $results['entries'] = Paginator::make($entries->all(), $total, Settings::get('entries_per_page'));
        $results['group_name'] = 'saved';

        return view('entries.display', $results);
    }

    public function saveContent()
    {
        $content = Content::findOrFail(Input::get('content'));

        $data = Auth::user()->data;
        $data->push('_saved_contents', $content->_id);

        return Response::json(array('status' => 'ok'));
    }

    public function removeContent()
    {
        $content = Content::findOrFail(Input::get('content'));

        $data = Auth::user()->data;
        $data->pull('_saved_contents', $content->_id);

        return Response::json(array('status' => 'ok'));
    }

    public function saveEntry()
    {
        $entry = Entry::findOrFail(Input::get('entry'));

        $data = Auth::user()->data;
        $data->push('_saved_entries', $entry->_id);

        return Response::json(array('status' => 'ok'));
    }

    public function removeEntry()
    {
        $entry = Entry::findOrFail(Input::get('entry'));

        $data = Auth::user()->data;
        $data->pull('_saved_entries', $entry->_id);

        return Response::json(array('status' => 'ok'));
    }

    public function getContents()
    {
        $page = Paginator::getCurrentPage();
        $ids = Auth::user()->data->_saved_contents;

        if (is_array($ids))
        {
            $total = count($ids);

            $contentIds = array_slice(array_reverse($ids), ($page - 1) * 20, 20);;

            $contents = Content::with('user')->whereIn('_id', $contentIds)->get();

            $contents->sortBy(function($content) use($contentIds) {
                return array_search($content->_id, $contentIds);
            });
        }
        else
        {
            $total = 0;
            $contents = new \Illuminate\Support\Collection();
        }

        return Paginator::make($contents->all(), $total, 20);
    }

    public function getEntries()
    {
        $page = Paginator::getCurrentPage();
        $ids = (array) Auth::user()->data->_saved_entries;

        $total = count($ids);

        $entryIds = array_slice(array_reverse($ids), ($page - 1) * 20, 20);;

        $entries = Entry::whereIn('_id', $entryIds)
            ->with('user')
            ->with(['replies.user' => function($q) { $q->remember(10); }])
            ->orderBy('created_at', 'desc')
            ->slice('_replies', -2)
            ->get();

        $entries->sortBy(function($entry) use($entryIds) {
            return array_search($entry->_id, $entryIds);
        });

        $results['blockedUsers'] = array();

        if (Auth::check())
            $results['blockedUsers'] = Auth::user()->blockedUsers();

        return Paginator::make($entries->all(), $total, 20);
    }

}
