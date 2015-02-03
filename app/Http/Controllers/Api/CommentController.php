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

    public function index()
    {
        $folderName = Input::get('folder');
        $groupName = Input::has('group') ? shadow(Input::get('group')) : 'all';

        if (Input::has('folder') && !class_exists('Folders\\'. studly_case($folderName)))
        {
            $user = Input::has('user') ? User::findOrFail(Input::get('user')) : Auth::user();
            $folder = Folder::findUserFolderOrFail($user->getKey(), Input::get('folder'));

            if (!$folder->public && (Auth::guest() || $user->getKey() != Auth::id()))
            {
                App::abort(404);
            }

            $builder = $folder->comments();
        }
        elseif (class_exists('Folders\\'. studly_case($groupName)))
        {
            $class = 'Folders\\'. studly_case($groupName);
            $fakeGroup = new $class;
            $builder = $fakeGroup->comments();
        }
        elseif (class_exists('Folders\\'. studly_case($folderName)))
        {
            $class = 'Folders\\'. studly_case($folderName);
            $fakeGroup = new $class;
            $builder = $fakeGroup->comments();
        }
        else
        {
            $group = Group::shadow($groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->comments();
        }

        $builder->with(['user', 'group']);

        // Sort using default field for selected tab, if sort field doesn't contain valid sortable field
        if (in_array(Input::get('sort'), ['uv', 'created_at']))
        {
            $builder->orderBy(Input::get('sort'), 'desc');
        }
        else
        {
            $builder->orderBy('created_at', 'desc');
        }

        // Time filter
        if (Input::get('time'))
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
            return Response::json(['status' => 'error', 'error' => 'Zostałeś zbanowany w tej grupie']);
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
            return Response::json(['status' => 'error', 'error' => 'Zostałeś zbanowany w tej grupie']);
        }

        $reply = new CommentReply();
        $reply->text = Input::get('text');
        $reply->user()->associate(Auth::user());

        $comment->replies()->save($comment);

        $content->increment('comments_count');

        return Response::json([
            'status' => 'ok', '_id' => $reply->getKey(), 'comment' => $reply
        ]);
    }

    public function edit(Request $request, $comment)
    {
        $this->validate($request, $comment->rules());

        $comment->update(Input::only('text'));

        return Response::json(['status' => 'ok', 'comment' => $comment]);
    }

    public function remove($comment)
    {
        if (Auth::id() === $comment->user_id
            || Auth::user()->isModerator($comment->group_id))
        {
            $comment->delete();

            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error'], 400);
    }

}
