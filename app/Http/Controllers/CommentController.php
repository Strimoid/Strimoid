<?php namespace Strimoid\Http\Controllers;

use App;
use Auth;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Input;
use Response;
use Route;
use Setting;
use Strimoid\Contracts\Repositories\FolderRepository;
use Strimoid\Contracts\Repositories\GroupRepository;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;

class CommentController extends BaseController
{
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
     * @param GroupRepository  $groups
     */
    public function __construct(FolderRepository $folders, GroupRepository $groups)
    {
        $this->groups = $groups;
        $this->folders = $folders;
    }

    public function showCommentsFromGroup($groupName = 'all')
    {
        // If user is on homepage, then use proper group
        if (! Route::input('groupname')) {
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

    public function getCommentSource()
    {
        $class = Input::get('type') == 'comment'
            ? Comment::class : CommentReply::class;

        $id = hashids_decode(Input::get('id'));
        $comment = $class::findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            App::abort(403, 'Access denied');
        }

        return Response::json(['status' => 'ok', 'source' => $comment->text_source]);
    }

    public function addComment(Request $request, $content)
    {
        $this->validate($request, Comment::rules());

        if (Auth::user()->isBanned($content->group)) {
            return Response::json([
                'status' => 'error',
                'error'  => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $comment = new Comment([
            'text' => Input::get('text'),
        ]);

        $comment->user()->associate(Auth::user());
        $comment->content()->associate($content);

        $comment->save();

        $comment = view('comments.widget', compact('comment'))->render();

        return Response::json(['status' => 'ok', 'comment' => $comment]);
    }

    /**
     * Add new reply to given Comment object.
     *
     * @param  Request  $request
     * @param  Comment  $parent
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addReply(Request $request, $parent)
    {
        $this->validate($request, CommentReply::rules());

        $content = $parent->content;

        if (Auth::user()->isBanned($content->group)) {
            return Response::json([
                'status' => 'error',
                'error'  => 'Zostałeś zbanowany w tej grupie',
            ]);
        }

        $comment = new CommentReply([
            'text' => Input::get('text'),
        ]);
        $comment->user()->associate(Auth::user());

        $parent->replies()->save($comment);

        $replies = view('comments.replies', ['replies' => $parent->replies])->render();

        return Response::json(['status' => 'ok', 'replies' => $replies]);
    }

    public function editComment(Request $request)
    {
        $class = (Input::get('type') == 'comment')
            ? Comment::class : CommentReply::class;
        $id = hashids_decode($request->input('id'));
        $comment = $class::findOrFail($id);

        if (! $comment->canEdit()) {
            app()->abort(403, 'Access denied');
        }

        $this->validate($request, CommentReply::rules());
        $comment->update(Input::only('text'));

        return Response::json(['status' => 'ok', 'parsed' => $comment->text]);
    }

    public function removeComment(Request $request)
    {
        $class = (Input::get('type') == 'comment')
            ? Comment::class : CommentReply::class;
        $id = hashids_decode($request->input('id'));
        $comment = $class::findOrFail($id);

        if ($comment->canRemove()) {
            $comment->delete();
            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }
}
