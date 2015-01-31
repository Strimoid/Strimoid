<?php namespace Strimoid\Http\Controllers;

use Auth, Input, Settings, Route, Response;
use Strimoid\Models\Content;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Group;

class CommentController extends BaseController {

    public function showComments($groupName = 'all')
    {
        $groupName = shadow($groupName);

        if (Auth::check() && !Route::input('group') && $groupName == 'all'
            && @Auth::user()->settings['homepage_subscribed'])
        {
            $groupName = 'subscribed';
        }

        if (Auth::guest() && in_array($groupName, ['subscribed', 'moderated', 'observed']))
        {
            return Redirect::route('login_form')->with('info_msg', 'Wybrana funkcja dostępna jest wyłącznie dla zalogowanych użytkowników.');
        }

        $className = 'Strimoid\\Models\\Folders\\'. studly_case($groupName);

        if (class_exists($className))
        {
            $builder = with(new $className)->comments();
        }
        else
        {
            $group = Group::shadow($groupName)->firstOrFail();
            $group->checkAccess();

            $builder = $group->comments();
        }

        $builder->orderBy('created_at', 'desc')->with(['user']);

        // Paginate
        $comments = $this->cachedPaginate($builder, Settings::get('entries_per_page'), 10);

        $group_name = $groupName;

        $blockedUsers = array();

        if (Auth::check())
        {
            $blockedUsers = (array) Auth::user()->blockedUsers();
        }

        return view('comments.list', compact('group', 'comments', 'group_name', 'blockedUsers'));
    }

    public function getCommentSource()
    {
        $class = (Input::get('type') == 'comment') ? 'Comment' : 'CommentReply';

        $comment = $class::findOrFail(Input::get('id'));

        if (Auth::id() !== $comment->user_id)
        {
            App::abort(403, 'Access denied');
        }

        return Response::json(['status' => 'ok', 'source' => $comment->text_source]);
    }

    public function addComment()
    {
        $validator = Comment::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $content = Content::findOrFail(Input::get('id'));

        if (Auth::user()->isBanned($content->group))
        {
            return Response::json(['status' => 'error', 'error' => 'Zostałeś zbanowany w tej grupie']);
        }

        $comment = new Comment([
            'text' => Input::get('text'),
        ]);

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

        $comment = view('comments.widget', compact('comment'))->render();

        return Response::json(['status' => 'ok', 'comment' => $comment]);
    }

    public function addReply()
    {
        $validator = CommentReply::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $parent = Comment::findOrFail(Input::get('id'));
        $content = $parent->content;

        if (Auth::user()->isBanned($content->group))
        {
            return Response::json(['status' => 'error', 'error' => 'Zostałeś zbanowany w tej grupie']);
        }

        $comment = new CommentReply([
            'text' => Input::get('text')
        ]);

        $comment->user()->associate(Auth::user());

        $parent->replies()->save($comment);

        $content->increment('comments_count');

        // Send notifications to mentioned users
        $this->sendNotifications(Input::get('text'), function($notification) use ($comment, $content){
            $notification->type = 'comment_reply';
            $notification->setTitle($comment->text);
            $notification->content()->associate($content);
            $notification->commentReply()->associate($comment);
            $notification->save(); // todo
        });

        $replies = view('comments.replies', ['replies' => $parent->replies])
            ->render();

        return Response::json(['status' => 'ok', 'replies' => $replies]);
    }

    public function editComment()
    {
        $class = (Input::get('type') == 'comment') ? 'Comment' : 'CommentReply';
        $comment = $class::findOrFail(Input::get('id'));

        if (Auth::user()->getKey() != $comment->user->getKey())
        {
            App::abort(403, 'Access denied');
        }

        $validator = $comment->validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

        $comment->deleteNotifications();

        $comment->update(Input::only('text'));

        // Send notifications to mentioned users
        $this->sendNotifications(Input::get('text'), function($notification) use ($comment){
            $notification->type = (Input::get('type') == 'comment_reply' ? 'comment_reply' : 'comment');
            $notification->setTitle($comment->text);

            if ($comment instanceof CommentReply)
            {
                $notification->content()->associate($comment->comment->content);
                $notification->commentReply()->associate($comment);
            }
            else
            {
                $notification->content()->associate($comment->content);
                $notification->comment()->associate($comment);
            }
        });

        return Response::json(['status' => 'ok', 'parsed' => $comment->text]);
    }

    public function removeComment()
    {
        $class = (Input::get('type') == 'comment') ? 'Comment' : 'CommentReply';
        $comment = $class::findOrFail(Input::get('id'));

        if (Auth::id() == $comment->user_id || Auth::user()->isModerator($comment->group_id))
        {
            $comment->delete();

            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error']);
    }

    /* === API === */

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

        $builder->with([
            'user' => function($q) { $q->remember(10); },
            'group' => function($q) { $q->remember(10); }
        ]);

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
            $time = new MongoDate(time() - intval(Input::get('time')) * 86400);
            $builder->where('created_at', '>', $time);
        }

        $perPage = 20;

        if (Input::has('per_page') && Input::get('per_page') > 0 &&  Input::get('per_page') <= 100)
        {
            $perPage = Input::get('per_page');
        }

        return $builder->paginate($perPage);
    }

    public function store(Content $content)
    {
        $validator = Comment::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

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

    public function storeReply(Comment $comment)
    {
        $validator = CommentReply::validate(Input::all());

        if ($validator->fails())
        {
            return Response::json(['status' => 'error', 'error' => $validator->messages()->first()]);
        }

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
            $notification->type = (Input::get('type') == 'comment_reply' ? 'comment_reply' : 'comment');
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
        if (Auth::id() === $comment->user_id || Auth::user()->isModerator($comment->group_id))
        {
            $comment->delete();

            return Response::json(['status' => 'ok']);
        }

        return Response::json(['status' => 'error'], 400);
    }
}