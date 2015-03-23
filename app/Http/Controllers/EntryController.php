<?php namespace Strimoid\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Response;
use Route;
use Setting;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Group;

class EntryController extends BaseController
{
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
     * @param GroupRepository  $groups
     */
    public function __construct(FolderRepository $folders, GroupRepository $groups)
    {
        $this->groups = $groups;
        $this->folders = $folders;
    }

    public function showEntriesFromGroup($groupName = null)
    {
        // If user is on homepage, then use proper group
        if (! Route::input('groupname')) {
            $groupName = $this->homepageGroup();
        }

        $group = $this->groups->requireByName($groupName);
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
            ->with(['group', 'user', 'replies.user']);

        $perPage = Setting::get('entries_per_page', 25);
        $entries = $builder->paginate($perPage);

        return view('entries.display', compact('entries'));
    }

    /**
     * Show entry view.
     *
     * @param  Entry  $entry
     * @return \Illuminate\View\View
     */
    public function showEntry($entry)
    {
        $entries = [$entry];
        view()->share('group', $entry->group);

        return view('entries.display', compact('entries'));
    }

    public function getEntryReplies($entry)
    {
        $replies = $entry->replies;

        return view('entries.replies', compact('entry', 'replies'));
    }

    public function getEntrySource()
    {
        if (Input::get('type') == 'entry_reply') {
            $entry = EntryReply::findOrFail(Input::get('id'));
        } else {
            $entry = Entry::findOrFail(Input::get('id'));
        }

        if (Auth::id() !== $entry->user_id) {
            App::abort(403, 'Access denied');
        }

        return Response::json(['status' => 'ok', 'source' => $entry->text_source]);
    }

    public function addEntry(Request $request)
    {
        $this->validate($request, Entry::rules());

        $group = Group::name(Input::get('groupname'))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group)) {
            return Response::json(['status' => 'error', 'error' => 'Zostałeś zbanowany w wybranej grupie']);
        }

        if ($group->type == 'announcements' && !Auth::user()->isModerator($group)) {
            return Response::json(['status' => 'error', 'error' => 'Nie możesz dodawać wpisów do wybranej grupy']);
        }

        $entry = new Entry();
        $entry->text = Input::get('text');
        $entry->user()->associate(Auth::user());
        $entry->group()->associate($group);
        $entry->save();

        return Response::json(['status' => 'ok']);
    }

    public function addReply(Request $request)
    {
        $this->validate($request, EntryReply::rules());

        $entryParent = Entry::findOrFail(Input::get('id'));

        if (Auth::user()->isBanned($entryParent->group)) {
            return Response::json(['status' => 'error', 'error' => 'Zostałeś zbanowany w wybranej grupie']);
        }

        $entry = new EntryReply();
        $entry->text = Input::get('text');
        $entry->user()->associate(Auth::user());
        $entryParent->replies()->save($entry);

        $replies = view('entries.replies', ['entry' => $entryParent, 'replies' => $entryParent->replies])
            ->render();

        return Response::json(['status' => 'ok', 'replies' => $replies]);
    }

    public function editEntry(Request $request)
    {
        if (Input::get('type') == 'entry_reply') {
            $entry = EntryReply::findOrFail(Input::get('id'));

            $lastReply = Entry::where('id', $entry->parent->getKey())
                ->project(['_replies' => ['$slice' => -1]])
                ->first()->replies->first();

            if ($lastReply->getKey() != $entry->getKey()) {
                return Response::json(['status' => 'error', 'error' => 'Pojawiła się już odpowiedź na twój wpis.']);
            }
        } else {
            $entry = Entry::findOrFail(Input::get('id'));

            if ($entry->replies_count > 0) {
                return Response::json(['status' => 'error', 'error' => 'Pojawiła się już odpowiedź na twój wpis.']);
            }
        }

        if (Auth::id() !== $entry->user_id) {
            App::abort(403, 'Access denied');
        }

        $this->validate($request, EntryReply::rules());

        $entry->text = Input::get('text');
        $entry->save();

        return Response::json(['status' => 'ok', 'parsed' => $entry->text]);
    }

    public function removeEntry($id = null)
    {
        $id = $id ? $id : Input::get('id');

        if (Input::get('type') == 'entry_reply') {
            $entry = EntryReply::findOrFail($id);
        } else {
            $entry = Entry::findOrFail($id);
        }

        if (Auth::id() === $entry->user_id
            || Auth::user()->isModerator($entry->group_id)) {
            if ($entry->delete()) {
                return Response::json(['status' => 'ok']);
            }
        }

        return Response::json(['status' => 'error'], 500);
    }
}
