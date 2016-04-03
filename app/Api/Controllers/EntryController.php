<?php namespace Strimoid\Api\Controllers;

use Auth;
use Illuminate\Http\Request;
use Input;
use Response;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Folder;
use Strimoid\Models\Group;

class EntryController extends BaseController
{
    public function index()
    {
        $folderName = Input::get('folder');
        $groupName = Input::has('group') ? shadow(Input::get('group')) : 'all';

        $className = 'Strimoid\\Models\\Folders\\'.studly_case($folderName ?: $groupName);

        if (Input::has('folder') && !class_exists('Folders\\'.studly_case($folderName))) {
            $user = Input::has('user') ? User::findOrFail(Input::get('user')) : Auth::user();
            $folder = Folder::findUserFolderOrFail($user->getKey(), Input::get('folder'));

            if (!$folder->public && (Auth::guest() || $user->getKey() != Auth::id())) {
                App::abort(404);
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
            ? between(Input::get('per_page'), 1, 100)
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
            Input::merge(['groupname' => Input::get('group')]);
        }

        $this->validate($request, Entry::rules());

        $group = Group::name(Input::get('group'))->firstOrFail();
        $group->checkAccess();

        if (Auth::user()->isBanned($group)) {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.'], 400);
        }

        if ($group->type == 'announcements' && !Auth::user()->isModerator($group)) {
            return Response::json(['status' => 'error', 'error' => 'Użytkownik nie może dodawać wpisów w tej grupie.'], 400);
        }

        $entry = new Entry();
        $entry->text = Input::get('text');
        $entry->user()->associate(Auth::user());
        $entry->group()->associate($group);
        $entry->save();

        return Response::json(['status' => 'ok', '_id' => $entry->getKey(), 'entry' => $entry]);
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

        if (Auth::user()->isBanned($entry->group)) {
            return Response::json([
                'status' => 'error',
                'error'  => 'Użytkownik został zbanowany w wybranej grupie.',
            ], 400);
        }

        $reply = new EntryReply();
        $reply->text = Input::get('text');
        $reply->user()->associate(Auth::user());
        $entry->replies()->save($reply);

        return Response::json(['status' => 'ok', '_id' => $reply->getKey(), 'reply' => $reply]);
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
            App::abort(403, 'Access denied');
        }

        $entry->update(Input::only('text'));

        return Response::json(['status' => 'ok', 'parsed' => $entry->text]);
    }

    /**
     * @param Entry $entry
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove($entry)
    {
        if (! $entry->canRemove()) {
            App::abort(403, 'Access denied');
        }

        $entry->delete();

        return Response::json(['status' => 'ok']);
    }
}
