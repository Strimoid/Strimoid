<?php

namespace Strimoid\Http\Controllers;

use Carbon\Carbon;
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
    public function __construct(private readonly \Illuminate\Contracts\Routing\ResponseFactory $responseFactory, private readonly \Illuminate\Auth\AuthManager $authManager, private readonly \Illuminate\Database\DatabaseManager $databaseManager, private readonly \Illuminate\Contracts\Config\Repository $configRepository)
    {
    }
    public function addVote(Request $request)
    {
        $object = $this->getObject($request->get('id'), $request->get('type'));

        if (!$object) {
            return $this->responseFactory->make('Object not found', 404);
        }

        if ($this->getVoteElement($object, $this->authManager->user())) {
            return $this->responseFactory->make('Already voted', 400);
        }

        if ($object->user->getKey() === $this->authManager->id()) {
            return $this->responseFactory->make('Do not cheat', 400);
        }

        if ($object instanceof ContentRelated) {
            $group = $object->content->group;
        } else {
            $group = $object->group;
        }
        if ($this->authManager->user()->isBanned($group)) {
            return $this->responseFactory->make('Banned', 400);
        }

        if (!apcu_add('anti_vote_flood.user.' . $this->authManager->id(), 1, 1)) {
            return $this->responseFactory->make('Don\'t flood', 400);
        }

        $uv = $object->uv;
        $dv = $object->dv;

        $up = $request->input('up') === 'true';

        $this->databaseManager->transaction(function () use ($up, $object): void {
            if ($up) {
                $object->increment('uv');
                $object->increment('score');

                // small trigger, needed for pushing contents to front page
                if ($object instanceof Content && !$object->frontpage_at
                    && $object->uv > $this->configRepository->get('strimoid.homepage.threshold')
                    && $object->created_at->diffInDays() < $this->configRepository->get('strimoid.homepage.time_limit')) {
                    $object->frontpage_at = Carbon::now();
                    $object->save();
                }
            } else {
                $object->increment('dv');
                $object->decrement('score');
            }

            $object->votes()->create([
                'created_at' => Carbon::now(),
                'user_id' => $this->authManager->id(),
                'up' => $up,
            ]);
        });

        if ($up) {
            $uv++;
        } else {
            $dv++;
        }

        return $this->responseFactory->json(['status' => 'ok', 'uv' => $uv, 'dv' => $dv]);
    }

    public function removeVote(\Illuminate\Http\Request $request)
    {
        $object = $this->getObject($request->input('id'), $request->input('type'));
        $vote = $this->getVoteElement($object, $this->authManager->user());

        if (!$vote) {
            return $this->responseFactory->make('Vote not found', 404);
        }

        $uv = $object->uv;
        $dv = $object->dv;

        $this->databaseManager->transaction(function () use ($vote, $object): void {
            if ($vote['up']) {
                $object->decrement('uv');
                $object->decrement('score');
            } else {
                $object->decrement('dv');
                $object->increment('score');
            }

            $object->votes()->where(['user_id' => $vote->user_id])->delete();
        });

        if ($vote['up']) {
            $uv--;
        } else {
            $dv--;
        }

        return $this->responseFactory->json(['status' => 'ok', 'uv' => $uv, 'dv' => $dv]);
    }

    public function getVoters(\Illuminate\Http\Request $request)
    {
        $object = $this->getObject($request->input('id'), $request->input('type'));

        if (!$object) {
            return $this->responseFactory->make('Object not found', 404);
        }

        $results = [];

        $up = $request->input('filter') === 'up';
        $votes = $object->votes()->where('up', $up ? true : false)->get();

        foreach ($votes as $vote) {
            $result['username'] = $vote->user->name;
            $result['time_ago'] = $vote->created_at->diffForHumans();
            $result['time'] = $vote->created_at->format('d/m/Y H:i:s');
            $result['up'] = $vote->up;

            $results[] = $result;
        }

        $results = array_reverse($results);

        return $this->responseFactory->json(['status' => 'ok', 'voters' => $results]);
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

    private function getObject(string $id, string $type)
    {
        $id = hashids_decode($id);
        return match ($type) {
            'content' => Content::findOrFail($id),
            'related' => ContentRelated::findOrFail($id),
            'entry' => Entry::findOrFail($id),
            'entry_reply' => EntryReply::findOrFail($id),
            'comment' => Comment::findOrFail($id),
            'comment_reply' => CommentReply::findOrFail($id),
            default => null,
        };
    }
}
