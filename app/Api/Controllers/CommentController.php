<?php

namespace Strimoid\Api\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends BaseController
{
    public function __construct(protected FolderRepository $folders, protected GroupRepository $groups, private \Illuminate\Contracts\Auth\Guard $guard, private \Illuminate\Contracts\Routing\ResponseFactory $responseFactory)
    {
    }

    public function index(Request $request)
    {
        if ($request->has('folder')) {
            $username = $request->input('user', $this->guard->id());
            $entity = $this->folders->getByName($username, $request->input('folder'));
        } else {
            $groupName = $request->input('group', 'all');
            $entity = $this->groups->getByName($groupName);
        }

        $sortBy = in_array($request->input('sort'), ['uv', 'created_at'])
            ? $request->input('sort') : 'created_at';

        $builder = $entity->comments($sortBy)->with([
            'user', 'group', 'replies', 'replies.user',
        ]);

        // Time filter
        if ($request->has('time')) {
            $builder->fromDaysAgo($request->input('time'));
        }

        $perPage = $request->has('per_page')
            ? between($request->input('per_page'), 1, 100)
            : 20;

        return $builder->paginate($perPage);
    }

    public function store(Request $request, $content): JsonResponse
    {
        $this->validate($request, Comment::validationRules());

        if (user()->isBanned($content->group)) {
            return $this->responseFactory->json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $comment = new Comment();
        $comment->text = $request->input('text');
        $comment->user()->associate(user());
        $comment->content()->associate($content);
        $comment->save();

        return $this->responseFactory->json([
            'status' => 'ok', '_id' => $comment->getKey(), 'comment' => $comment,
        ]);
    }

    public function storeReply(Request $request, $comment): JsonResponse
    {
        $this->validate($request, CommentReply::validationRules());
        $content = $comment->content;

        if (user()->isBanned($content->group)) {
            return $this->responseFactory->json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $reply = new CommentReply();
        $reply->text = $request->input('text');
        $reply->user()->associate(user());
        $comment->replies()->save($comment);

        return $this->responseFactory->json([
            'status' => 'ok', '_id' => $reply->getKey(), 'comment' => $reply,
        ]);
    }

    public function edit(Request $request, Comment $comment): Response
    {
        $this->authorize('edit', $comment);
        $this->validate($request, $comment->validationRules());

        $comment->update($request->only('text'));

        return $this->responseFactory->json(['status' => 'ok', 'comment' => $comment]);
    }

    public function remove(Comment $comment): Response
    {
        $this->authorize('remove', $comment);

        $comment->delete();

        return $this->responseFactory->json(['status' => 'ok']);
    }
}
