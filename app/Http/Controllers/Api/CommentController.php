<?php namespace Strimoid\Http\Controllers\Api; 

use App, Auth, Input, Response;
use Illuminate\Http\Request;
use Strimoid\Contracts\FolderRepository;
use Strimoid\Contracts\GroupRepository;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;

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

    public function index()
    {
        if (Input::has('folder'))
        {
            $username = Input::get('user', Auth::id());
            $entity = $this->folders->getByName($username, Input::get('folder'));
        }
        else
        {
            $groupName = Input::get('group', 'all');
            $entity = $this->groups->getByName($groupName);
        }

        $sortBy = in_array(Input::get('sort'), ['uv', 'created_at'])
            ? Input::get('sort') : 'created_at';

        $builder = $entity->comments($sortBy)->with(['user', 'group']);

        // Time filter
        if (Input::has('time'))
        {
            $builder->fromDaysAgo(Input::get('time'));
        }

        $perPage = Input::has('per_page')
            ? between(Input::get('per_page'), 1, 100)
            : 20;

        return $builder->paginate($perPage);
    }

    public function store(Request $request, $content)
    {
        $this->validate($request, Comment::rules());

        if (Auth::user()->isBanned($content->group))
        {
            return Response::json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie'
            ]);
        }

        $comment = new Comment();
        $comment->text = Input::get('text');
        $comment->user()->associate(Auth::user());
        $comment->content()->associate($content);
        $comment->group()->associate($content->group);
        $comment->save();

        return Response::json([
            'status' => 'ok', '_id' => $comment->getKey(), 'comment' => $comment
        ]);
    }

    public function storeReply(Request $request, $comment)
    {
        $this->validate($request, CommentReply::rules());
        $content = $comment->content;

        if (Auth::user()->isBanned($content->group))
        {
            return Response::json([
                'status' => 'error',
                'error' => 'Zostałeś zbanowany w tej grupie'
            ]);
        }

        $reply = new CommentReply();
        $reply->text = Input::get('text');
        $reply->user()->associate(Auth::user());
        $comment->replies()->save($comment);

        return Response::json([
            'status' => 'ok', '_id' => $reply->getKey(), 'comment' => $reply
        ]);
    }

    /**
     * Edit comment text.
     *
     * @param  Request  $request
     * @param  Comment  $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, $comment)
    {
        $this->validate($request, $comment->rules());

        if ( ! $comment->canEdit()) App::abort(403);

        $comment->update(Input::only('text'));

        return Response::json(['status' => 'ok', 'comment' => $comment]);
    }

    /**
     * Remove comment.
     *
     * @param  Comment  $comment
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove($comment)
    {
        if ( ! $comment->canRemove()) App::abort(403);

        $comment->delete();
        return Response::json(['status' => 'ok']);
    }

}
