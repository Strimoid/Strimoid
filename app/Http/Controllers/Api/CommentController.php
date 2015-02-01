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

        if (Auth::guest() && in_array($groupName, ['subscribed', 'moderated', 'observed', 'saved']))
        {
            App::abort(403, 'Group available only for logged in users');
        }

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

        $perPage = 20;

        if (Input::has('per_page')
            && Input::get('per_page') > 0
            &&  Input::get('per_page') <= 100)
        {
            $perPage = Input::get('per_page');
        }

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

        // Send notifications to mentioned users
        $this->sendNotifications(Input::get('text'), function($notification) use ($content, $comment)
        {
            $notification->type = 'comment';
            $notification->setTitle($comment->text);
            $notification->content()->associate($content);
            $notification->comment()->associate($comment);
            $notification->save(); // todo
        });

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

        // Send notifications to mentioned users
        $this->sendNotifications(Input::get('text'), function($notification) use ($content, $reply){
            $notification->type = 'comment_reply';
            $notification->setTitle($reply->text);
            $notification->content()->associate($content);
            $notification->commentReply()->associate($reply);
            $notification->save(); // todo
        });

        return Response::json([
            'status' => 'ok', '_id' => $reply->getKey(), 'comment' => $reply
        ]);
    }

    public function edit($comment)
    {
        $validator = $comment->validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $comment->deleteNotifications();
        $comment->update(Input::only('text'));

        // Send notifications to mentioned users
        $this->sendNotifications(Input::get('text'), function($notification) use ($comment){
            $notification->type = Input::get('type') == 'comment_reply'
                ? 'comment_reply' : 'comment';
            $notification->setTitle($comment->text);

            if ($comment instanceof CommentReply)
            {
                $notification->content()->associate($comment->comment->content);
                $notification->commentReply()->associate($comment);
            }
            else
            {
                $notification->comment()->associate($comment);
            }
        });

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
