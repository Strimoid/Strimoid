<?php

use Helpers\Base58;

Event::listen('auth.login', function($user)
{
    $user->last_login = new DateTime();

    $user->save();
});

/* Prevent sending notifications by blocked users */

Notification::saving(function($notification)
{
    if ($notification->user->isBlockingUser($notification->sourceUser))
    {
        return false;
    }
});

/*
 * Realtime notifications
 * using WebSocket server
*/

Notification::created(function($notification)
{
    foreach($notification->users as $user)
    {
        WS::send(json_encode([
            'topic' => 'u.'. $user->_id,
            'tag' => Base58::encode($notification->_id),
            'type' => $notification->getTypeDescription(),
            'title' => $notification->title,
            'img' => $notification->getThumbnailPath(),
            'url' => $notification->getURL(true)
        ]));
    }
});

/*
 * Android notifications
 * using Google Cloud Messaging
*/

Notification::created(function($notification)
{
    foreach($notification->users as $user)
    {
        $user = User::find($user->_id);

        if ($user->gcm_regid)
        {
            try {
                $client = new Guzzle\Http\Client('http://android.googleapis.com', [
                    'timeout'         => 5,
                    'connect_timeout' => 5
                ]);
                $client->setDefaultOption('headers/Authorization', 'key=AIzaSyC2sUrZ14yB_3ZTq2PPCy66nR5zaK_KtH4');

                $request = $client->post('gcm/send', null, json_encode([
                        'registration_ids' => [$notification->user->gcm_regid],
                        'data' => [
                            'tag' => substr($notification->_id, -6),
                            'type' => $notification->getTypeDescription(),
                            'title' => $notification->title,
                            'img' => $notification->getThumbnailPath(),
                        ]])
                );

                $request->setHeader('Content-Type', 'application/json');
                $request->getCurlOptions()->set(CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                $request->send();
            }
            catch(Exception $e) {}
        }
    }
});

/*
 * Realtime entry loading
 * using WebSocket server
*/

Entry::created(function($entry)
{
    WS::send(json_encode([
        'topic' => 'entries',
        'id' => $entry->_id,
        'author' => $entry->user_id,
        'author_color' => $entry->user->getColoredName(),
        'avatar' => $entry->user->getAvatarPath(),
        'group' => $entry->group_id,
        'text' => $entry->text,
        'time' => $entry->getLocalTime()
    ]));
});

/* User actions log */

Content::created(function($content)
{
    $action = new UserAction();
    $action->user()->associate(Auth::user());
    $action->type = UserAction::TYPE_CONTENT;
    $action->content()->associate($content);
    $action->save();
});

Comment::created(function($comment)
{
    $action = new UserAction();
    $action->user()->associate(Auth::user());
    $action->type = UserAction::TYPE_COMMENT;
    $action->comment()->associate($comment);
    $action->save();
});

CommentReply::created(function($comment)
{
    $action = new UserAction();
    $action->user()->associate(Auth::user());
    $action->type = UserAction::TYPE_COMMENT_REPLY;
    $action->commentReply()->associate($comment);
    $action->save();
});

Entry::created(function($entry)
{
    $action = new UserAction();
    $action->user()->associate(Auth::user());
    $action->type = UserAction::TYPE_ENTRY;
    $action->entry()->associate($entry);
    $action->save();
});

EntryReply::created(function($entry)
{
    $action = new UserAction();
    $action->user()->associate(Auth::user());
    $action->type = UserAction::TYPE_ENTRY_REPLY;
    $action->entryReply()->associate($entry);
    $action->save();
});

/* Cache cleaning */

/* IRC Notification */

User::created(function($user)
{
    try {
        Guzzle::post('http://localhost:8421/channels/strimoid', [], 'Mamy nowego użytkownika '. $user->_id .'!')
            ->send();
    }
    catch(Exception $e) {}
});


