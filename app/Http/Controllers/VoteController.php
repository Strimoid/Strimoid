<?php namespace Strimoid\Http\Controllers;

use Auth;
use Carbon;
use Illuminate\Http\Request;
use Strimoid\Models\Comment;
use Strimoid\Models\CommentReply;
use Strimoid\Models\Content;
use Strimoid\Models\ContentRelated;
use Strimoid\Models\Entry;
use Strimoid\Models\EntryReply;
use Strimoid\Models\User;

class VoteController extends BaseController
{
    public function addVote(Request $request)
    {
        $object = $this->getObject($request->get('id'), $request->get('type'));

        if (!$object) {
            return response()->make('Object not found', 404);
        }

        if ($this->getVoteElement($object, Auth::user())) {
            return response()->make('Already voted', 400);
        }

        if ($object->user->getKey() == Auth::id()) {
            return response()->make('Do not cheat', 400);
        }

        if ($object instanceof ContentRelated) {
            $group = $object->content->group;
        } else {
            $group = $object->group;
        }
        if (Auth::user()->isBanned($group)) {
            return response()->make('Banned', 400);
        }

        if (!apcu_add('anti_vote_flood.user.'.Auth::id(), 1, 1)) {
            return response()->make('Don\'t flood', 400);
        }

        $uv = $object->uv;
        $dv = $object->dv;

        $up = request('up') === 'true';

        if ($up) {
            $object->increment('uv');
            $object->increment('score');

            // small hack, as increment function doesn't update object :(
            $uv++;

            // small trigger, needed for pushing contents to front page
            if ($object instanceof Content && !$object->frontpage_at
                    &&  $object->uv > config('strimoid.homepage.threshold')
                    && $object->created_at->diffInDays() < config('strimoid.homepage.time_limit')) {
                $object->frontpage_at = new Carbon();
                $object->save();
            }
        } else {
            $object->increment('dv');
            $object->decrement('score');
            $dv++;
        }

        $object->votes()->create([
            'created_at'    => new Carbon(),
            'user_id'       => Auth::id(),
            'up'            => $up,
        ]);

        return response()->json(['status' => 'ok', 'uv' => $uv, 'dv' => $dv]);
    }

    public function removeVote()
    {
        $object = $this->getObject(request('id'), request('type'));
        $vote = $this->getVoteElement($object, Auth::user());

        if (!$vote) {
            return response()->make('Vote not found', 404);
        }

        $uv = $object->uv;
        $dv = $object->dv;

        if ($vote['up']) {
            $object->decrement('uv');
            $object->decrement('score');
            $uv--;
        } else {
            $object->decrement('dv');
            $object->increment('score');
            $dv--;
        }

        $object->votes()->where(['user_id' => $vote->user_id])->delete();

        return response()->json(['status' => 'ok', 'uv' => $uv, 'dv' => $dv]);
    }

    public function getVoters()
    {
        $object = $this->getObject(request('id'), request('type'));

        if (!$object) {
            return response()->make('Object not found', 404);
        }

        $results = [];

        $up = request('filter') === 'up';
        $votes = $object->votes()->where('up', $up ? true : false)->get();

        foreach ($votes as $vote) {
            $result['username'] = $vote->user->name;
            $result['time_ago'] = $vote->created_at->diffForHumans();
            $result['time'] = $vote->created_at->format('d/m/Y H:i:s');
            $result['up'] = $vote->up;

            $results[] = $result;
        }

        $results = array_reverse($results);

        return response()->json(['status' => 'ok', 'voters' => $results]);
    }

    private function getVoteElement($object, User $user)
    {
        if (!$object->votes()) {
            return false;
        }

        $vote = $object->votes()->where('user_id', $user->getKey())->first();

        if (!$vote) {
            return false;
        }

        return $vote;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private function getObject(string $id, string $type)
    {
        $id = \Hashids::decode($id);
        $id = current($id);

        switch ($type) {
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
}
