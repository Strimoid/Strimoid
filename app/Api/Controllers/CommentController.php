<?php

namespace Strimoid\Api\Controllers;

use Illuminate\Http\Request;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends BaseController
{
    protected FolderRepository $folders;

    protected GroupRepository $groups;

    public function __construct(FolderRepository $folders, GroupRepository $groups)
    {
        $this->groups = $groups;
        $this->folders = $folders;
    }

    public function index(Request $request)
    {
        if ($request->has('folder')) {
            $username = request('user', auth()->id());
            $entity = $this->folders->getByName($username, request('folder'));
        } else {
            $groupName = request('group', 'all');
            $entity = $this->groups->getByName($groupName);
        }

        $sortBy = in_array(request('sort'), ['uv', 'created_at'])
            ? request('sort') : 'created_at';

        $builder = $entity->comments($sortBy)->with([
            'user', 'group', 'replies', 'replies.user',
        ]);

        // Time filter
        if ($request->has('time')) {
            $builder->fromDaysAgo(request('time'));
        }

        $perPage = $request->has('per_page')
            ? between(request('per_page'), 1, 100)
            : 20;

        return $builder->paginate($perPage);
    }

    public function store(Request $request, $content)
    {
        $this->validate($request, Comment::validationRules());

        if (user()->isBanned($content->group)) {
            return response()->json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $comment = new Comment();
        $comment->text = request('text');
        $comment->user()->associate(user());
        $comment->content()->associate($content);
        $comment->save();

        return response()->json([
            'status' => 'ok', '_id' => $comment->getKey(), 'comment' => $comment,
        ]);
    }

    public function storeReply(Request $request, $comment)
    {
        $this->validate($request, CommentReply::validationRules());
        $content = $comment->content;

        if (user()->isBanned($content->group)) {
            return response()->json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $reply = new CommentReply();
        $reply->text = request('text');
        $reply->user()->associate(user());
        $comment->replies()->save($comment);

        return response()->json([
            'status' => 'ok', '_id' => $reply->getKey(), 'comment' => $reply,
        ]);
    }

    public function edit(Request $request, Comment $comment): Response
    {
        $this->authorize('edit', $comment);
        $this->validate($request, $comment->validationRules());

        $comment->update($request->only('text'));

        return response()->json(['status' => 'ok', 'comment' => $comment]);
    }

    public function remove(Comment $comment): Response
    {
        $this->authorize('remove', $comment);

        $comment->delete();

        return response()->json(['status' => 'ok']);
    }
}
