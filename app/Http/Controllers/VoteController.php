<?php namespace Strimoid\Http\Controllers;

use App, Auth, Carbon, Input, Response;
use Strimoid\Models\Content;
use Strimoid\Models\ContentRelated;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\Vote;

class VoteController extends BaseController {

    public function addVote() {
        $object = $this->getObject(Input::get('id'), Input::get('type'));

        if (!$object)
        {
            return Response::make('Object not found', 404);
        }

        if ($this->getVoteElement($object, Auth::user()))
        {
            return Response::make('Already voted', 400);
        }

        if ($object->user->getKey() == Auth::user()->getKey())
        {
            return Response::make('Do not cheat', 400);
        }

        if (Auth::user()->isBanned($this->getObjectGroup($object)))
        {
            return Response::make('Banned', 400);
        }

        if (!apc_add('anti_vote_flood.user.'. Auth::id(), 1, 1))
        {
            return Response::make('Don\'t flood', 400);
        }

        $uv = $object->uv;
        $dv = $object->dv;

        $up = Input::get('up') === 'true';

        if ($up)
        {
            $object->increment('uv');
            $object->increment('score');

            // small hack, as increment function doesn't update object :(
            $uv++;

            // small trigger, needed for pushing contents to front page
            if ($object instanceof Content && $object->uv > 5
                    && !$object->frontpage_at && $object->created_at->diffInDays() < 5)
            {
                $object->frontpage_at = new Carbon;
                $object->save();
            }
        }
        else
        {
            $object->increment('dv');
            $object->decrement('score');
            $dv++;
        }

        $vote = new Vote([
            'user_id' => Auth::id(),
            'up'      => $up,
        ]);

        $object->votes()->save($vote);

        return Response::json(['status' => 'ok', 'uv' => $uv, 'dv' => $dv]);
    }

    public function removeVote() {
        $object = $this->getObject(Input::get('id'), Input::get('type'));
        $vote = $this->getVoteElement($object, Auth::user());

        if (!$vote) return Response::make('Vote not found', 404);

        $uv = $object->uv;
        $dv = $object->dv;

        if ($vote['up'])
        {
            $object->decrement('uv');
            $object->decrement('score');
            $uv--;
        }
        else
        {
            $object->decrement('dv');
            $object->increment('score');
            $dv--;
        }

        $vote->delete();

        return Response::json(['status' => 'ok', 'uv' => $uv, 'dv' => $dv]);
    }

    public function getVoters()
    {
        $object = $this->getObject(Input::get('id'), Input::get('type'));

        if (!$object)
        {
            return Response::make('Object not found', 404);
        }

        $results = array();

        $up = Input::get('filter') === 'up';
        $votes = $object->votes()->where('up', $up ? true : false)->get();

        foreach ($votes as $vote)
        {
            $result['username'] = $vote->user->name;
            $result['time_ago'] = $vote->created_at->diffForHumans();
            $result['time'] = $vote->created_at->format('d/m/Y H:i:s');
            $result['up'] = $vote->up;

            $results[] = $result;
        }

        $results = array_reverse($results);

        return Response::json(['status' => 'ok', 'voters' => $results]);
    }

    private function getVoteElement($object, $user)
    {
        if (!$object->votes) return false;

        $vote = $object->votes()->where('user_id', $user->getKey())->first();

        if (!$vote) return false;

        return $vote;
    }

    private function getObject($id, $type)
    {
        switch ($type)
        {
            case 'content':
                return Content::findOrFail($id);
            case 'related':
                return ContentRelated::findOrFail($id);
            case 'entry':
                return Entry::findOrFail($id);
            case 'entry_reply':
                return EntryReply::findOrFail($id);
            case 'comment':
                return Comment::findOrFail($id);
            case 'comment_reply':
                return CommentReply::findOrFail($id);
        }
    }

    private function getObjectGroup($object)
    {
        switch (Input::get('type'))
        {
            case 'content':
            case 'entry':
                return $object->group;
            case 'related':
                return $object->content->group;
            case 'entry_reply':
                return $object->entry->group;
            case 'comment':
                return $object->content->group;
            case 'comment_reply':
                return $object->comment->content->group;
        }
    }

}