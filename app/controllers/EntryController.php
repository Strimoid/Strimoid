<?php

class EntryController extends BaseController {

    public function showEntries($groupName = 'all')
    {
        $groupName = shadow($groupName);

        $results['blockedUsers'] = array();

        // Show popular instead of all as homepage for guests
        if (Auth::guest() && !Route::input('group')) {
            $groupName = 'popular';
        }

        if (Auth::check()) {
            $results['blockedUsers'] = Auth::user()->blockedUsers();

            // Set proper group name if user is having subscribed set as his homepage
            if (!Route::input('group') && $groupName == 'all' && @Auth::user()->settings['homepage_subscribed'])
            {
                $groupName = 'subscribed';
            }
        }

        if (Auth::guest() && in_array($groupName, ['subscribed', 'moderated', 'observed']))
        {
            return Redirect::route('login_form')
                ->with('info_msg', 'Wybrana funkcja dostępna jest wyłącznie dla zalogowanych użytkowników.');
        }

        if (Route::input('folder'))
        {
            $user = Route::input('user') ? User::findOrFail(Route::input('user')) : Auth::user();
            $folder = Folder::findUserFolder($user->_id, Route::input('folder'));

            if (!$folder->public && (Auth::guest() || $user->_id != Auth::user()->_id))
            {
                App::abort(404);
            }

            $builder = $folder->entries();
            $results['folder'] = $folder;
        }
        elseif (class_exists('Groups\\'. studly_case($groupName)))
        {
            $class = 'Groups\\'. studly_case($groupName);
            $fakeGroup = new $class;
            $builder = $fakeGroup->entries();

            $results['fakeGroup'] = $fakeGroup;
        }
        else
        {
            $group = Group::where('shadow_urlname', $groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->entries();
            $results['group'] = $group;
        }

        $builder->orderBy('created_at', 'desc')
            //->with(['user', 'replies.user' => function($q) { $q->remember(10); }])
            //->project(['_replies' => ['$slice' => -2]]);
        ;

        // Paginate
        /*
        $entries = $this->cachedPaginate($builder, Settings::get('entries_per_page'), 10, ['*'], function($entries) {
            $entries->load(['user', 'replies.user']);
        });
        */

        $entries = $builder->paginate(Settings::get('entries_per_page'));

        $results['entries'] = $entries;
        $results['group_name'] = $groupName;

        return View::make('entries.display', $results);
    }

    public function showEntry($id)
    {
        if (Route::current()->getName() == 'single_entry_reply')
        {
            $entry = Entry::where('_replies._id', $id)->with('user')->with('replies.user')->firstOrFail();
        }
        else
        {
            $entry = Entry::with('user')->with('replies.user')->findOrFail($id);
        }

        $entries = array($entry);
        $group = $entry->group;

        return View::make('entries.display', compact('entries', 'group'));
    }

    public function getEntryReplies($id)
    {
        $entry = Entry::findOrFail($id);
        $replies = $entry->replies;

        return View::make('entries.replies', compact('entry', 'replies'));
    }

    public function getEntrySource()
    {
        if (Input::get('type') == 'entry_reply')
        {
            $entry = EntryReply::findOrFail(Input::get('id'));
        }
        else
        {
            $entry = Entry::findOrFail(Input::get('id'));
        }

        if (Auth::user()->getKey() != $entry->user_id)
        {
            App::abort(403, 'Access denied');
        }

        return Response::json(['status' => 'ok', 'source' => $entry->text_source]);
    }

    public function addEntry()
    {
        $validator = Entry::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $group = Group::where('shadow_urlname', shadow(Input::get('groupname')))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group))
        {
            return Response::json(['status' => 'error', 'error' => 'Zostałeś zbanowany w wybranej grupie']);
        }

        if ($group->type == Group::TYPE_ANNOUNCEMENTS && !Auth::user()->isModerator($group))
        {
            return Response::json(['status' => 'error', 'error' => 'Nie możesz dodawać wpisów do wybranej grupy']);
        }

        $entry = new Entry();
        $entry->text = Input::get('text');
        $entry->user()->associate(Auth::user());
        $entry->group()->associate($group);
        $entry->save();

        // Send notifications to mentioned users
        $this->sendNotifications(Input::get('text'), function($notification) use ($entry)
        {
            $notification->type = 'entry';
            $notification->setTitle($entry->text);
            $notification->entry()->associate($entry);
            $notification->save(); // todo
        });

        return Response::json(['status' => 'ok']);
    }

    public function addReply() {
        $validator = Validator::make(Input::all(), ['text' => 'required|min:1|max:2500']);

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $entryParent = Entry::findOrFail(Input::get('id'));

        if (Auth::user()->isBanned($entryParent->group))
        {
            return Response::json(['status' => 'error', 'error' => 'Zostałeś zbanowany w wybranej grupie']);
        }

        $entry = new EntryReply();
        $entry->text = Input::get('text');
        $entry->user()->associate(Auth::user());
        $entryParent->replies()->save($entry);

        // Send notifications to mentions users
        $this->sendNotifications(Input::get('text'), function($notification) use ($entry)
        {
            $notification->type = 'entry_reply';
            $notification->setTitle($entry->text);
            $notification->entryReply()->associate($entry);
            $notification->save(); // todo
        });

        $entryParent->increment('replies_count');

        $replies = View::make('entries.replies', ['entry' => $entryParent, 'replies' => $entryParent->replies])
            ->render();

        return Response::json(['status' => 'ok', 'replies' => $replies]);
    }

    public function editEntry()
    {
        if (Input::get('type') == 'entry_reply')
        {
            $entry = EntryReply::findOrFail(Input::get('id'));

            $lastReply = Entry::where('_id', $entry->entry->_id)
                ->project(['_replies' => ['$slice' => -1]])
                ->first()->replies->first();

            if ($lastReply->_id != $entry->_id)
            {
                return Response::json(['status' => 'error', 'error' => 'Pojawiła się już odpowiedź na twój wpis.']);
            }
        }
        else
        {
            $entry = Entry::findOrFail(Input::get('id'));

            if ($entry->getRepliesCount() > 0)
            {
                return Response::json(['status' => 'error', 'error' => 'Pojawiła się już odpowiedź na twój wpis.']);
            }
        }

        if (Auth::user()->_id != $entry->user_id)
        {
            App::abort(403, 'Access denied');
        }

        $validator = Validator::make(Input::all(), ['text' => 'required|min:1|max:2500']);

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $entry->deleteNotifications();
        $entry->text = Input::get('text');
        $entry->save();

        // Send notifications to mentions users
        $this->sendNotifications(Input::get('text'), function($notification) use ($entry){
            $notification->type = (Input::get('type') == 'entry_reply' ? 'entry_reply' : 'entry');
            $notification->setTitle($entry->text);

            if (Input::get('type') == 'entry_reply')
                $notification->entryReply()->associate($entry);
            else
                $notification->entry()->associate($entry);

            $notification->save(); // todo
        });

        return Response::json(['status' => 'ok', 'parsed' => $entry->text]);
    }

    public function removeEntry($id = null)
    {
        $id = $id ? $id : Input::get('id');

        if (Input::get('type') == 'entry_reply')
            $entry = EntryReply::findOrFail($id);
        else
            $entry = Entry::findOrFail($id);

        if (Auth::user()->_id == $entry->user_id || Auth::user()->isModerator($entry->group_id))
        {
            if ($entry->delete()) {
                return Response::json(array('status' => 'ok'));
            }

        }

        return Response::json(array('status' => 'error'));
    }

    /* === API === */

    public function getIndex()
    {
        $groupName = Input::has('group') ? shadow(Input::get('group')) : 'all';

        if (Auth::guest() && in_array($groupName, ['subscribed', 'moderated', 'observed', 'saved']))
            App::abort(403, 'Group available only for logged in users');

        if (Input::has('folder'))
        {
            $user = Input::has('user') ? User::findOrFail(Input::get('user')) : Auth::user();
            $folder = Folder::findUserFolderOrFail($user->_id, Input::get('folder'));

            if (!$folder->public && (Auth::guest() || $user->_id != Auth::user()->_id))
            {
                App::abort(404);
            }

            $builder = $folder->entries();
            $results['folder'] = $folder;
        }
        elseif (class_exists('Groups\\'. studly_case($groupName)))
        {
            $class = 'Groups\\'. studly_case($groupName);
            $fakeGroup = new $class;
            $builder = $fakeGroup->entries();

            $builder->orderBy('sticky_global', 'desc');

            $results['fakeGroup'] = $fakeGroup;
        }
        else
        {
            $group = Group::where('shadow_urlname', $groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->entries();

            // Allow group moderators to stick contents
            $builder->orderBy('sticky_group', 'desc');

            $results['group'] = $group;
        }

        $builder->with(['user', 'group', 'replies', 'replies.user'])->orderBy('created_at', 'desc');

        $perPage = 20;

        if (Input::has('per_page') && Input::get('per_page') > 0 &&  Input::get('per_page') <= 100)
        {
            $perPage = Input::get('per_page');
        }

        return $builder->paginate($perPage);
    }

    public function show(Entry $entry)
    {
        $entry->load(['user', 'group', 'replies', 'replies.user']);

        return $entry;
    }

    public function store()
    {
        $validator = Entry::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $group = Group::where('shadow_urlname', shadow(Input::get('group')))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group))
        {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.']);
        }

        if ($group->type == Group::TYPE_ANNOUNCEMENTS && !Auth::user()->isModerator($group))
        {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik nie może dodawać wpisów w tej grupie.']);
        }

        $entry = new Entry();
        $entry->text = Input::get('text');
        $entry->user()->associate(Auth::user());
        $entry->group()->associate($group);
        $entry->save();

        $this->sendNotifications(Input::get('text'), function($notification) use ($entry){
            $notification->type = 'entry';
            $notification->setTitle($entry->text);
            $notification->entry()->associate($entry);
            $notification->save(); // todo
        });

        return Response::json(['status' => 'ok', '_id' => $entry->_id, 'entry' => $entry]);
    }

    public function storeReply(Entry $entry)
    {
        $validator = EntryReply::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        if (Auth::user()->isBanned($entry->group))
        {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.']);
        }

        $reply = new EntryReply();
        $reply->text = Input::get('text');
        $reply->user()->associate(Auth::user());
        $entry->replies()->save($entry);

        // Send notifications to mentions users
        $this->sendNotifications(Input::get('text'), function($notification) use ($reply)
        {
            $notification->type = 'entry_reply';
            $notification->setTitle($reply->text);
            $notification->entryReply()->associate($reply);
            $notification->save(); // todo
        });

        $entry->increment('replies_count');

        return Response::json(['status' => 'ok', '_id' => $reply->_id, 'reply' => $reply]);
    }

    public function edit($entry)
    {
        $validator = EntryReply::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        if (!$entry->isLast())
        {
            return Response::json(['status' => 'error', 'error' => 'Pojawiła się już odpowiedź na twój wpis.']);
        }

        if (Auth::user()->_id != $entry->user_id)
        {
            App::abort(403, 'Access denied');
        }

        $entry->deleteNotifications();
        $entry->update(Input::only('text'));

        // Send notifications to mentions users
        $this->sendNotifications(Input::get('text'), function($notification) use ($entry){
            $notification->type = ($entry instanceof Entry) ? 'entry' : 'entry_reply';
            $notification->setTitle($entry->text);

            if ($entry instanceof Entry)
            {
                $notification->entryReply()->associate($entry);
            }
            else
            {
                $notification->entry()->associate($entry);
            }

            $notification->save(); // todo
        });

        return Response::json(['status' => 'ok', 'parsed' => $entry->text]);
    }

    public function remove($entry)
    {
        if (Auth::user()->_id == $entry->user_id || Auth::user()->isModerator($entry->group_id))
        {
            if ($entry->delete()) {
                return Response::json(['status' => 'ok']);
            }

        }

        return Response::json(['status' => 'error']);
    }

}
