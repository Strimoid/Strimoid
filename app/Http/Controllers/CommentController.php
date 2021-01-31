<?php

namespace Strimoid\Http\Controllers;

use App;
use Auth;
use Illuminate\Http\Request;
use Response;
use Route;
use Setting;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;

class CommentController extends BaseController
{
    protected FolderRepository $folders;

    protected GroupRepository $groups;

    public function __construct(FolderRepository $folders, GroupRepository $groups)
    {
        $this->groups = $groups;
        $this->folders = $folders;
    }

    public function showCommentsFromGroup(string $groupName = null)
    {
        // If user is on homepage, then use proper group
        if (!Route::input('groupname')) {
            $groupName = $this->homepageGroup();
        }

        $group = $this->groups->getByName($groupName);
        view()->share('group', $group);

        if (Auth::guest() && $group->isPrivate) {
            return redirect()->guest('login');
        }

        $builder = $group->comments();

        return $this->showComments($builder);
    }

    public function showCommentsFromFolder()
    {
        $userName = Route::input('user') ?: Auth::id();
        $folderName = Route::input('folder');

        $folder = $this->folders->getByName($userName, $folderName);
        view()->share('folder', $folder);

        $builder = $folder->comments();

        return $this->showComments($builder);
    }

    protected function showComments($builder)
    {
        $builder->orderBy('created_at', 'desc')
                ->with(['user', 'vote']);

        $perPage = Setting::get('entries_per_page', 25);
        $comments = $builder->paginate($perPage);

        return view('comments.list', compact('comments'));
    }

    public function getCommentSource(Request $request)
    {
        $class = $request->get('type') == 'comment' ? Comment::class : CommentReply::class;

        $id = hashids_decode($request->get('id'));
        $comment = $class::findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            App::abort(403, 'Access denied');
        }

        return Response::json(['status' => 'ok', 'source' => $comment->text_source]);
    }

    public function addComment(Request $request, Content $content)
    {
        $this->validate($request, Comment::validationRules());

        if (Auth::user()->isBanned($content->group)) {
            return Response::json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $comment = new Comment([
            'text' => $request->get('text'),
        ]);

        $comment->user()->associate(Auth::user());
        $comment->content()->associate($content);

        $comment->save();

        $comment = view('comments.widget', compact('comment'))->render();

        return Response::json(['status' => 'ok', 'comment' => $comment]);
    }

    public function addReply(Request $request, Comment $parent): \Symfony\Component\HttpFoundation\Response
    {
        $this->validate($request, CommentReply::validationRules());

        $content = $parent->content;

        if (Auth::user()->isBanned($content->group)) {
            return Response::json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $comment = new CommentReply([
            'text' => $request->get('text'),
        ]);
        $comment->user()->associate(Auth::user());

        $parent->replies()->save($comment);

        $replies = view('comments.replies', ['replies' => $parent->replies])->render();

        return Response::json(['status' => 'ok', 'replies' => $replies]);
    }

    public function editComment(Request $request)
    {
        $class = $request->get('type') === 'comment'
            ? Comment::class : CommentReply::class;
        $id = hashids_decode($request->input('id'));
        $comment = $class::findOrFail($id);

        $this->authorize('edit', $comment);
        $this->validate($request, CommentReply::validationRules());

        $comment->update($request->only('text'));

        return Response::json(['status' => 'ok', 'parsed' => $comment->text]);
    }

    public function removeComment(Request $request)
    {
        $class = $request->get('type') === 'comment'
            ? Comment::class : CommentReply::class;
        $id = hashids_decode($request->input('id'));
        $comment = $class::findOrFail($id);

        $this->authorize('remove', $comment);

        if ($comment->delete()) {
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }
}
