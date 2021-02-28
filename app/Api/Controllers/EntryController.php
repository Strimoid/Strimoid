<?php

namespace Strimoid\Api\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Folder;
use Strimoid\Models\Group;

class EntryController extends BaseController
{
    public function __construct(private \Illuminate\Contracts\Auth\Guard $guard, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }
    public function index(Request $request)
    {
        $folderName = $request->input('folder');
        $groupName = $request->has('group') ? shadow($request->input('group')) : 'all';

        $className = 'Strimoid\\Models\\Folders\\' . Str::studly($folderName ?: $groupName);

        if ($request->has('folder') && !class_exists('Folders\\' . Str::studly($folderName))) {
            $user = $request->has('user') ? User::findOrFail($request->input('user')) : user();
            $folder = Folder::findUserFolderOrFail($user->getKey(), $request->input('folder'));

            if (!$folder->public && ($this->guard->guest() || $user->getKey() !== $this->guard->id())) {
                abort(404);
            }

            $builder = $folder->entries();
        } elseif (class_exists($className)) {
            $fakeGroup = new $className();
            $builder = $fakeGroup->entries();
        } else {
            $group = Group::name($groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->entries();
        }

        $builder->with(['user', 'group', 'replies', 'replies.user'])
            ->orderBy('created_at', 'desc');

        $perPage = $request->has('per_page')
            ? between($request->input('per_page'), 1, 100)
            : 20;

        return $builder->paginate($perPage);
    }

    public function show(Entry $entry)
    {
        $entry->load(['user', 'group']);

        // loading of embedded relations is broken atm :(
        return array_merge($entry->toArray(), ['replies' => $entry->replies->toArray()]);
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->has('group')) {
            $request->merge(['groupname' => $request->input('group')]);
        }

        $this->validate($request, Entry::validationRules());

        $group = Group::name($request->input('group'))->firstOrFail();
        $group->checkAccess();

        if (user()->isBanned($group)) {
            return $this->responseFactory->json(['status' => 'error', 'error' => 'Użytkownik został zbanowany w wybranej grupie.'], 400);
        }

        if ($group->type === 'announcements' && !user()->isModerator($group)) {
            return $this->responseFactory->json(['status' => 'error', 'error' => 'Użytkownik nie może dodawać wpisów w tej grupie.'], 400);
        }

        $entry = new Entry();
        $entry->text = $request->input('text');
        $entry->user()->associate(user());
        $entry->group()->associate($group);
        $entry->save();

        return $this->responseFactory->json(['status' => 'ok', '_id' => $entry->getKey(), 'entry' => $entry]);
    }

    public function storeReply(Request $request, Entry $entry): JsonResponse
    {
        $this->validate($request, EntryReply::validationRules());

        if (user()->isBanned($entry->group)) {
            return $this->responseFactory->json([
                'status' => 'error',
                'error' => 'Użytkownik został zbanowany w wybranej grupie.',
            ], 400);
        }

        $reply = new EntryReply();
        $reply->text = $request->input('text');
        $reply->user()->associate(user());
        $entry->replies()->save($reply);

        return $this->responseFactory->json(['status' => 'ok', '_id' => $reply->getKey(), 'reply' => $reply]);
    }

    public function edit(Request $request, Entry $entry): JsonResponse
    {
        $this->authorize('edit', $entry);
        $this->validate($request, $entry->validationRules());

        if (!$entry->canEdit()) {
            abort(403, 'Access denied');
        }

        $entry->update($request->only('text'));

        return $this->responseFactory->json(['status' => 'ok', 'parsed' => $entry->text]);
    }

    public function remove(Entry $entry): JsonResponse
    {
        $this->authorize('remove', $entry);

        $entry->delete();

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
