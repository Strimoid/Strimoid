<?php

namespace Strimoid\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Settings\Facades\Setting;

class CommentController extends BaseController
{
    public function __construct(protected FolderRepository $folders, protected GroupRepository $groups, private \Illuminate\Routing\Router $router, private \Illuminate\Contracts\View\Factory $viewFactory, private \Illuminate\Auth\AuthManager $authManager, private \Illuminate\Routing\Redirector $redirector, private \Illuminate\Foundation\Application $application, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }

    public function showCommentsFromGroup(string $groupName = null)
    {
        // If user is on homepage, then use proper group
        if (!$this->router->input('groupname')) {
            $groupName = $this->homepageGroup();
        }

        $group = $this->groups->getByName($groupName);
        $this->viewFactory->share('group', $group);

        if ($group->isPrivate && $this->authManager->guest()) {
            return $this->redirector->guest('login');
        }

        $builder = $group->comments();

        return $this->showComments($builder);
    }

    public function showCommentsFromFolder()
    {
        $userName = $this->router->input('user') ?: $this->authManager->id();
        $folderName = $this->router->input('folder');

        $folder = $this->folders->getByName($userName, $folderName);
        $this->viewFactory->share('folder', $folder);

        $builder = $folder->comments();

        return $this->showComments($builder);
    }

    protected function showComments($builder)
    {
        $builder->orderBy('created_at', 'desc')
                ->with(['user', 'vote']);

        $perPage = Setting::get('entries_per_page');
        $comments = $builder->paginate($perPage);

        return $this->viewFactory->make('comments.list', compact('comments'));
    }

    public function getCommentSource(Request $request): JsonResponse
    {
        $class = $request->get('type') === 'comment' ? Comment::class : CommentReply::class;

        $id = hashids_decode($request->get('id'));
        $comment = $class::findOrFail($id);

        if ($this->authManager->id() !== $comment->user_id) {
            $this->application->abort(403, 'Access denied');
        }

        return $this->responseFactory->json(['status' => 'ok', 'source' => $comment->text_source]);
    }

    public function addComment(Request $request, Content $content): JsonResponse
    {
        $this->validate($request, Comment::validationRules());

        if ($this->authManager->user()->isBanned($content->group)) {
            return $this->responseFactory->json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $comment = new Comment([
            'text' => $request->get('text'),
        ]);

        $comment->user()->associate($this->authManager->user());
        $comment->content()->associate($content);

        $comment->save();

        $comment = $this->viewFactory->make('comments.widget', compact('comment'))->render();

        return $this->responseFactory->json(['status' => 'ok', 'comment' => $comment]);
    }

    public function addReply(Request $request, Comment $parent): \Symfony\Component\HttpFoundation\Response
    {
        $this->validate($request, CommentReply::validationRules());

        $content = $parent->content;

        if ($this->authManager->user()->isBanned($content->group)) {
            return $this->responseFactory->json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $comment = new CommentReply([
            'text' => $request->get('text'),
        ]);
        $comment->user()->associate($this->authManager->user());

        $parent->replies()->save($comment);

        $replies = $this->viewFactory->make('comments.replies', ['replies' => $parent->replies])->render();

        return $this->responseFactory->json(['status' => 'ok', 'replies' => $replies]);
    }

    public function editComment(Request $request): JsonResponse
    {
        $class = $request->get('type') === 'comment'
            ? Comment::class : CommentReply::class;
        $id = hashids_decode($request->input('id'));
        $comment = $class::findOrFail($id);

        $this->authorize('edit', $comment);
        $this->validate($request, CommentReply::validationRules());

        $comment->update($request->only('text'));

        return $this->responseFactory->json(['status' => 'ok', 'parsed' => $comment->text]);
    }

    public function removeComment(Request $request): JsonResponse
    {
        $class = $request->get('type') === 'comment'
            ? Comment::class : CommentReply::class;
        $id = hashids_decode($request->input('id'));
        $comment = $class::findOrFail($id);

        $this->authorize('remove', $comment);

        if ($comment->delete()) {
            return $this->responseFactory->json(['status' => 'ok']);
        }

        return $this->responseFactory->json(['status' => 'error']);
    }
}
