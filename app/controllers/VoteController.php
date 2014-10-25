<?php

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

        if (!apc_add('anti_vote_flood.user.'. Auth::user()->_id, 1, 1))
        {
            App::abort(400, 'Don\'t flood.');
        }

        $uv = $object->uv;
        $dv = $object->dv;

        if (Input::get('up') == 'true')
        {
            $object->increment('uv');
            $object->increment('score');

            // small hack, as increment function doesn't update object :(
            $uv++;

            // small trigger, needed for pushing contents to front page
            if ($object instanceof Content && $object->uv > 8
                    && !$object->frontpage_at && $object->created_at->diffInDays() < 5)
            {
                $object->frontpage_at = new MongoDate();
                $object->save();
            }

            $object->mpush('votes', ['user_id' => Auth::user()->_id, 'created_at' => new MongoDate(), 'up' => true]);
        }
        else
        {
            $object->increment('dv');
            $object->decrement('score');

            // small hack, as increment function doesn't update object :(
            $dv++;

            $object->mpush('votes', ['user_id' => Auth::user()->_id, 'created_at' => new MongoDate(), 'up' => false]);
        }

        return Response::json(['status' => 'ok', 'uv' => $uv, 'dv' => $dv]);
    }

    public function removeVote() {
        $object = $this->getObject(Input::get('id'), Input::get('type'));
        $vote = $this->getVoteElement($object, Auth::user());

        if (!$vote)
        {
            return Response::make('Vote not found', 404);
        }

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

        $object->mpull('votes', $vote);

        return Response::json(['status' => 'ok', 'uv' => $uv, 'dv' => $dv]);
    }

    public function getVoters()
    {
        $object = $this->getObject(Input::get('id'), Input::get('type'));

        if (!$object)
        {
            return Response::make('Object not found', 404);
        }

        if (!$object->votes)
        {
            return Response::json(['status' => 'ok', 'voters' => []]);
        }

        $results = array();

        foreach ($object->votes as $vote)
        {
            if (Input::get('filter') == 'up' && !$vote['up'])
                continue;

            if (Input::get('filter') == 'down' && $vote['up'])
                continue;

            $result['username'] = $vote['user_id'];
            $result['time_ago'] = Carbon::createFromTimeStamp($vote['created_at']->sec)->diffForHumans();
            $result['time'] = Carbon::createFromTimeStamp($vote['created_at']->sec)->format('d/m/Y H:i:s');
            $result['up'] = $vote['up'];

            $results[] = $result;
        }

        $results = array_reverse($results);

        return Response::json(['status' => 'ok', 'voters' => $results]);
    }

    private function getVoteElement($object, $user)
    {
        if (!$object->votes)
        {
            return false;
        }

        $pos = array_search($user->_id, array_column($object->votes, 'user_id'));

        if ($pos === false)
            return false;

        return $object->votes[$pos];
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