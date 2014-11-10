<?php

Event::listen('auth.login', function($user)
{
    $user->last_login = Carbon::now();
    $user->last_ip = Request::getClientIp();

    $user->save();
});

/*
 * Realtime notifications
 * using WebSocket server
*/

Notification::created(function($notification)
{
    foreach($notification->targets as $target)
    {
        WS::send(json_encode([
            'topic' => 'u.'. $target->user_id,
            'tag' => mid_to_b58($notification->_id),
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
    foreach($notification->targets as $target)
    {
        if ($target->user->gcm_regid)
        {
            try {
                $client = new Guzzle\Http\Client('http://android.googleapis.com', [
                    'timeout'         => 5,
                    'connect_timeout' => 5
                ]);
                $client->setDefaultOption('headers/Authorization', 'key=AIzaSyC2sUrZ14yB_3ZTq2PPCy66nR5zaK_KtH4');

                $request = $client->post('gcm/send', null, json_encode([
                        'registration_ids' => [$target->user->gcm_regid],
                        'data' => [
                            'tag' => mid_to_b58($notification->_id),
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
    $action->user_id = $content->user_id;
    $action->type = UserAction::TYPE_CONTENT;
    $action->content()->associate($content);
    $action->save();
});

Comment::created(function($comment)
{
    $action = new UserAction();
    $action->user_id = $comment->user_id;
    $action->type = UserAction::TYPE_COMMENT;
    $action->comment()->associate($comment);
    $action->save();
});

CommentReply::created(function($comment)
{
    $action = new UserAction();
    $action->user_id = $comment->user_id;
    $action->type = UserAction::TYPE_COMMENT_REPLY;
    $action->commentReply()->associate($comment);
    $action->save();
});

Entry::created(function($entry)
{
    $action = new UserAction();
    $action->user_id = $entry->user_id;
    $action->type = UserAction::TYPE_ENTRY;
    $action->entry()->associate($entry);
    $action->save();
});

EntryReply::created(function($entry)
{
    $action = new UserAction();
    $action->user_id = $entry->user_id;
    $action->type = UserAction::TYPE_ENTRY_REPLY;
    $action->entryReply()->associate($entry);
    $action->save();
});

/* Cache cleaning */

/* IRC Notification */

User::created(function($user)
{
    try {
        Guzzle::post('http://localhost:8421/channels/strimoid', [], 'Mamy nowego użytkownika '. $user->_id .'!');
    }
    catch(Exception $e) {}
});


