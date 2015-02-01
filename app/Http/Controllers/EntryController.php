<?php namespace Strimoid\Http\Controllers;

use Auth, Input, Route, Settings, Response;
use Strimoid\Contracts\FolderRepository;
use Strimoid\Contracts\GroupRepository;
use Strimoid\Models\Group;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;

class EntryController extends BaseController {

    /**
     * @var FolderRepository
     */
    protected $folders;

    /**
     * @var GroupRepository
     */
    protected $groups;

    /**
     * @param FolderRepository $folders
     * @param GroupRepository $groups
     */
    public function __construct(FolderRepository $folders, GroupRepository $groups)
    {
        $this->groups = $groups;
        $this->folders = $folders;
    }

    public function showEntriesFromGroup($groupName = null)
    {
        // If user is on homepage, then use proper group
        if ( ! Route::input('group'))
        {
            $groupName = $this->homepageGroup();
        }

        $group = $this->groups->getByName($groupName);
        view()->share('group', $group);

        $builder = $group->entries();
        return $this->showEntries($builder);
    }

    public function showEntriesFromFolder()
    {
        $userName = Route::input('user') ?: Auth::id();
        $folderName = Route::input('folder');

        $folder = $this->folders->getByName($userName, $folderName);
        view()->share('folder', $folder);

        $builder = $folder->entries();
        return $this->showEntries($builder);
    }

    protected function showEntries($builder)
    {
        $builder->orderBy('created_at', 'desc')
            ->with(['user', 'replies.user']);

        $perPage = Settings::get('entries_per_page');
        $entries = $builder->paginate($perPage);

        return view('entries.display', compact('entries'));
    }

    public function showEntry($id)
    {
        if (Route::currentRouteName() == 'single_entry_reply')
        {
            $entry = Entry::where('_replies._id', $id)
                ->with(['user', 'replies.user'])->firstOrFail();
        }
        else
        {
            $entry = Entry::with('user')
                ->with('replies.user')->findOrFail($id);
        }

        $entries = [$entry];
        $group = $entry->group;

        return view('entries.display', compact('entries', 'group'));
    }

    public function getEntryReplies($id)
    {
        $entry = Entry::findOrFail($id);
        $replies = $entry->replies;

        return view('entries.replies', compact('entry', 'replies'));
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

        if (Auth::id() !== $entry->user_id)
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

        $group = Group::shadow(Input::get('groupname'))->firstOrFail();
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
        $validator = EntryReply::validate(Input::all());

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

        $replies = view('entries.replies', ['entry' => $entryParent, 'replies' => $entryParent->replies])
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

            if ($entry->replies_count > 0)
            {
                return Response::json(['status' => 'error', 'error' => 'Pojawiła się już odpowiedź na twój wpis.']);
            }
        }

        if (Auth::user()->getKey() !== $entry->user_id) App::abort(403, 'Access denied');

        $validator = EntryReply::validate(Input::all());

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

        if (Auth::id() === $entry->user_id || Auth::user()->isModerator($entry->group_id))
        {
            if ($entry->delete()) {
                return Response::json(['status' => 'ok']);
            }

        }

        return Response::json(['status' => 'error'], 500);
    }

    /* === API === */

    public function getIndex()
    {
        $folderName = Input::get('folder');
        $groupName = Input::has('group') ? shadow(Input::get('group')) : 'all';

        $className = 'Strimoid\\Models\\Folders\\'. studly_case($folderName ?: $groupName);

        if (Input::has('folder') && !class_exists('Folders\\'. studly_case($folderName)))
        {
            $user = Input::has('user') ? User::findOrFail(Input::get('user')) : Auth::user();
            $folder = Folder::findUserFolderOrFail($user->_id, Input::get('folder'));

            if (!$folder->public && (Auth::guest() || $user->_id != Auth::user()->_id))
            {
                App::abort(404);
            }

            $builder = $folder->entries();
        }
        elseif (class_exists($className))
        {
            $fakeGroup = new $className;
            $builder = $fakeGroup->entries();

            $builder->orderBy('sticky_global', 'desc');
        }
        else
        {
            $group = Group::shadow($groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->entries();

            // Allow group moderators to stick contents
            $builder->orderBy('sticky_group', 'desc');
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
        $entry->load(['user', 'group']);

        // loading of embedded relations is broken atm :(
        $entry = array_merge($entry->toArray(), ['replies' => $entry->replies->toArray()]);

        return $entry;
    }

    public function store()
    {
        if (Input::has('group'))
        {
            Input::merge(['groupname' => Input::get('group')]);
        }

        $validator = Entry::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()], 400);
        }

        $group = Group::shadow(Input::get('group'))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group))
        {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.'], 400);
        }

        if ($group->type == Group::TYPE_ANNOUNCEMENTS && !Auth::user()->isModerator($group))
        {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik nie może dodawać wpisów w tej grupie.'], 400);
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
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()], 400);
        }

        if (Auth::user()->isBanned($entry->group))
        {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.'], 400);
        }

        $reply = new EntryReply();
        $reply->text = Input::get('text');
        $reply->user()->associate(Auth::user());
        $entry->replies()->save($reply);

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
