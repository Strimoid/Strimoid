<?php namespace Strimoid\Http\Controllers;

use Auth, Input, Settings, Route, Response;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Strimoid\Contracts\FolderRepository;
use Strimoid\Contracts\GroupRepository;
use Strimoid\Models\Content;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;

class CommentController extends BaseController {

    use ValidatesRequests;

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
     * @param GroupRepository $groups
     */
    public function __construct(FolderRepository $folders, GroupRepository $groups)
    {
        $this->groups = $groups;
        $this->folders = $folders;
    }

    public function showCommentsFromGroup($groupName = 'all')
    {
        // If user is on homepage, then use proper group
        if ( ! Route::input('group'))
        {
            $groupName = $this->homepageGroup();
        }

        $group = $this->groups->getByName($groupName);
        view()->share('group', $group);

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
        $builder->orderBy('created_at', 'desc')->with(['user']);

        $perPage = Settings::get('entries_per_page');
        $comments = $builder->paginate($perPage);

        return view('comments.list', compact('comments'));
    }

    public function getCommentSource()
    {
        $class = Input::get('type') == 'comment'
            ? Comment::class : CommentReply::class;

        $comment = $class::findOrFail(Input::get('id'));

        if (Auth::id() !== $comment->user_id) App::abort(403, 'Access denied');

        return Response::json(['status' => 'ok', 'source' => $comment->text_source]);
    }

    public function addComment(Request $request)
    {
        $this->validate($request, Comment::rules());

        $content = Content::findOrFail(Input::get('id'));

        if (Auth::user()->isBanned($content->group))
        {
            return Response::json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie'
            ]);
        }

        $comment = new Comment([
            'text' => Input::get('text'),
        ]);

        $comment->user()->associate(Auth::user());
        $comment->content()->associate($content);
        $comment->group()->associate($content->group);

        $comment->save();

        $comment = view('comments.widget', compact('comment'))->render();

        return Response::json(['status' => 'ok', 'comment' => $comment]);
    }

    public function addReply(Request $request)
    {
        $this->validate($request, CommentReply::rules());

        $parent = Comment::findOrFail(Input::get('id'));
        $content = $parent->content;

        if (Auth::user()->isBanned($content->group))
        {
            return Response::json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie'
            ]);
        }

        $comment = new CommentReply([
            'text' => Input::get('text')
        ]);

        $comment->user()->associate(Auth::user());

        $parent->replies()->save($comment);

        $content->increment('comments_count');

        $replies = view('comments.replies', ['replies' => $parent->replies])
            ->render();

        return Response::json(['status' => 'ok', 'replies' => $replies]);
    }

    public function editComment(Request $request)
    {
        $class = (Input::get('type') == 'comment')
            ? Comment::class : CommentReply::class;
        $comment = $class::findOrFail(Input::get('id'));

        if ( ! $comment->canEdit()) App::abort(403, 'Access denied');

        $this->validate($request, CommentReply::rules());
        $comment->update(Input::only('text'));

        return Response::json(['status' => 'ok', 'parsed' => $comment->text]);
    }

    public function removeComment()
    {
        $class = (Input::get('type') == 'comment') ? 'Comment' : 'CommentReply';
        $class = 'Strimoid\Models\\'. $class;
        $comment = $class::findOrFail(Input::get('id'));

        if ($comment->canRemove())
        {
            $comment->delete();

            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

}