<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Group;
use Strimoid\Settings\Facades\Setting;

class EntryController extends BaseController
{
    public function __construct(protected FolderRepository $folders, protected GroupRepository $groups, private readonly \Illuminate\Routing\Router $router, private readonly \Illuminate\Contracts\View\Factory $viewFactory, private readonly \Illuminate\Auth\AuthManager $authManager, private readonly \Illuminate\Routing\Redirector $redirector, private readonly \Illuminate\Contracts\Auth\Guard $guard, private readonly \Illuminate\Contracts\Routing\ResponseFactory $responseFactory, private readonly \Illuminate\Contracts\Auth\Access\Gate $gate)
    {
    }

    public function showEntriesFromGroup($groupName = null)
    {
        // If user is on homepage, then use proper group
        if (!$this->router->input('groupname')) {
            $groupName = $this->homepageGroup();
        }

        $group = $this->groups->requireByName($groupName);
        $this->viewFactory->share('group', $group);

        if ($group->isPrivate && $this->authManager->guest()) {
            return $this->redirector->guest('login');
        }

        $builder = $group->entries();

        return $this->showEntries($builder);
    }

    public function showEntriesFromFolder()
    {
        $userName = $this->router->input('user') ?: $this->authManager->id();
        $folderName = $this->router->input('folder');

        $folder = $this->folders->getByName($userName, $folderName);
        $this->viewFactory->share('folder', $folder);

        $builder = $folder->entries();

        return $this->showEntries($builder);
    }

    protected function showEntries($builder)
    {
        $builder->orderBy('created_at', 'desc')
            ->with(['group', 'user', 'replies', 'replies.user']);

        if ($this->authManager->check()) {
            $builder->with('vote', 'userSave', 'replies.vote');
        }

        $perPage = Setting::get('entries_per_page');
        $entries = $builder->paginate($perPage);

        return $this->viewFactory->make('entries.display', compact('entries'));
    }

    public function showEntry(Entry $entry): View
    {
        $entries = [$entry];
        $this->viewFactory->share('group', $entry->group);

        return $this->viewFactory->make('entries.display', compact('entries'));
    }

    public function getEntryReplies($entry)
    {
        $replies = $entry->replies;

        return $this->viewFactory->make('entries.replies', compact('entry', 'replies'));
    }

    public function getEntrySource(Request $request)
    {
        $id = hashids_decode($request->get('id'));
        $class = $request->get('type') === 'entry'
            ? Entry::class : EntryReply::class;

        $entry = $class::findOrFail($id);

        if ($this->guard->id() !== $entry->user_id) {
            abort(403);
        }

        return $this->responseFactory->json(['status' => 'ok', 'source' => $entry->text_source]);
    }

    public function addEntry(Request $request)
    {
        $this->validate($request, Entry::validationRules());

        $group = Group::name($request->get('groupname'))->firstOrFail();
        $group->checkAccess();

        if ($this->authManager->user()->isBanned($group)) {
            return $this->responseFactory->json(['status' => 'error', 'error' => 'Zostałeś zbanowany w wybranej grupie']);
        }

        if ($group->type === 'announcements' && !$this->authManager->user()->isModerator($group)) {
            return $this->responseFactory->json(['status' => 'error', 'error' => 'Nie możesz dodawać wpisów do wybranej grupy']);
        }

        $entry = new Entry();
        $entry->text = $request->get('text');
        $entry->user()->associate($this->authManager->user());
        $entry->group()->associate($group);
        $entry->save();

        $html = $this->viewFactory->make('entries.widget', ['entry' => $entry, 'isReply' => false])->render();

        return $this->responseFactory->json(['status' => 'ok', 'entry' => $html]);
    }

    public function addReply(Request $request, $entry)
    {
        $this->validate($request, EntryReply::validationRules());

        if (user()->isBanned($entry->group)) {
            return $this->responseFactory->json(['status' => 'error', 'error' => 'Zostałeś zbanowany w wybranej grupie']);
        }

        $reply = new EntryReply();
        $reply->text = $request->get('text');
        $reply->user()->associate($this->authManager->user());
        $entry->replies()->save($reply);

        $replies = $this->viewFactory->make('entries.replies', ['entry' => $entry, 'replies' => $entry->replies])
            ->render();

        return $this->responseFactory->json(['status' => 'ok', 'replies' => $replies]);
    }

    public function editEntry(Request $request)
    {
        $id = hashids_decode($request->input('id'));
        $class = $request->input('type') === 'entry_reply' ? EntryReply::class : Entry::class;

        $entry = $class::findOrFail($id);

        $policyDecision = $this->gate->inspect('edit', $entry);

        if ($policyDecision->denied()) {
            return $this->responseFactory->json([
                'status' => 'error',
                'error' => $policyDecision->message(),
            ]);
        }

        $this->validate($request, EntryReply::validationRules());

        $entry->text = $request->get('text');
        $entry->save();

        return $this->responseFactory->json(['status' => 'ok', 'parsed' => $entry->text]);
    }

    public function removeEntry(Request $request, $id = null)
    {
        $id = hashids_decode($id ?: $request->get('id'));
        $class = $request->input('type') === 'entry_reply' ? EntryReply::class : Entry::class;

        /** @var Entry|EntryReply $entry */
        $entry = $class::findOrFail($id);

        $this->authorize('remove', $entry);

        if ($entry->delete()) {
            return $this->responseFactory->json(['status' => 'ok']);
        }

        return $this->responseFactory->json(['status' => 'error'], 500);
    }
}
