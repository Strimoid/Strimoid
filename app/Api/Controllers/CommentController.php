<?php

namespace Strimoid\Api\Controllers;

use Illuminate\Http\Request;
use Input;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;

class CommentController extends BaseController
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

    public function index()
    {
        if (Input::has('folder')) {
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
        if (Input::has('time')) {
            $builder->fromDaysAgo(request('time'));
        }

        $perPage = Input::has('per_page')
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

    /**
     * Edit comment text.
     *
     * @param Request $request Request instance
     * @param Comment $comment Comment instance
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, $comment)
    {
        $this->validate($request, $comment->validationRules());

        if (!$comment->canEdit()) {
            abort(403);
        }

        $comment->update(Input::only('text'));

        return response()->json(['status' => 'ok', 'comment' => $comment]);
    }

    /**
     * Remove comment.
     *
     * @param Comment $comment Comment instance
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove($comment)
    {
        if (!$comment->canRemove()) {
            abort(403);
        }

        $comment->delete();

        return response()->json(['status' => 'ok']);
    }
}
