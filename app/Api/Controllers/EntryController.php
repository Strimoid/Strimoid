<?php namespace Strimoid\Api\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Folder;
use Strimoid\Models\Group;

class EntryController extends BaseController
{
    public function index()
    {
        $folderName = request('folder');
        $groupName = Input::has('group') ? shadow(request('group')) : 'all';

        $className = 'Strimoid\\Models\\Folders\\'.studly_case($folderName ?: $groupName);

        if (Input::has('folder') && !class_exists('Folders\\'.studly_case($folderName))) {
            $user = Input::has('user') ? User::findOrFail(request('user')) : user();
            $folder = Folder::findUserFolderOrFail($user->getKey(), request('folder'));

            if (!$folder->public && (auth()->guest() || $user->getKey() != auth()->id())) {
                abort(404);
            }

            $builder = $folder->entries();
        } elseif (class_exists($className)) {
            $fakeGroup = new $className();
            $builder = $fakeGroup->entries();

            //$builder->orderBy('sticky_global', 'desc');
        } else {
            $group = Group::name($groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->entries();

            // Allow group moderators to stick contents
            //$builder->orderBy('sticky_group', 'desc');
        }

        $builder->with(['user', 'group', 'replies', 'replies.user'])
            ->orderBy('created_at', 'desc');

        $perPage = Input::has('per_page')
            ? between(request('per_page'), 1, 100)
            : 20;

        return $builder->paginate($perPage);
    }

    public function show(Entry $entry)
    {
        $entry->load(['user', 'group']);

        // loading of embedded relations is broken atm :(
        $entry = array_merge($entry->toArray(), ['replies' => $entry->replies->toArray()]);

        return $entry;
    }

    public function store(Request $request)
    {
        if (Input::has('group')) {
            Input::merge(['groupname' => request('group')]);
        }

        $this->validate($request, Entry::rules());

        $group = Group::name(request('group'))->firstOrFail();
        $group->checkAccess();

        if (user()->isBanned($group)) {
            return response()->json(['status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.'], 400);
        }

        if ($group->type == 'announcements' && !user()->isModerator($group)) {
            return response()->json(['status' => 'error', 'error' => 'Użytkownik nie może dodawać wpisów w tej grupie.'], 400);
        }

        $entry = new Entry();
        $entry->text = request('text');
        $entry->user()->associate(user());
        $entry->group()->associate($group);
        $entry->save();

        return response()->json(['status' => 'ok', '_id' => $entry->getKey(), 'entry' => $entry]);
    }

    /**
     * @param Request $request
     * @param Entry   $entry
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function storeReply(Request $request, $entry)
    {
        $this->validate($request, EntryReply::rules());

        if (user()->isBanned($entry->group)) {
            return response()->json([
                'status' => 'error',
                'error'  => 'Użytkownik został zbanowany w wybranej grupie.',
            ], 400);
        }

        $reply = new EntryReply();
        $reply->text = request('text');
        $reply->user()->associate(user());
        $entry->replies()->save($reply);

        return response()->json(['status' => 'ok', '_id' => $reply->getKey(), 'reply' => $reply]);
    }

    /**
     * @param Request $request
     * @param Entry   $entry
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, $entry)
    {
        $this->validate($request, $entry->rules());

        if (! $entry->canEdit()) {
            abort(403, 'Access denied');
        }

        $entry->update(Input::only('text'));

        return response()->json(['status' => 'ok', 'parsed' => $entry->text]);
    }

    /**
     * @param Entry $entry
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove($entry)
    {
        if (! $entry->canRemove()) {
            abort(403, 'Access denied');
        }

        $entry->delete();

        return response()->json(['status' => 'ok']);
    }
}
