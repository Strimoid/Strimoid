<?php

namespace Strimoid\Api\Controllers;

use Illuminate\Http\Request;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Group;
use Strimoid\Models\GroupBan;
use Strimoid\Models\GroupModerator;

class GroupController extends BaseController
{
    public function index(Request $request)
    {
        $builder = Group::where('type', '!=', 'private');

        if ($request->has('name')) {
            $builder->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if (in_array($request->get('sort'), ['created_at', 'subscribers_count'])) {
            $builder->orderBy($request->get('sort'), 'desc');
        } else {
            $builder->orderBy('created_at', 'desc');
        }

        return $builder->paginate(100);
    }

    public function show($groupName)
    {
        $group = Group::name($groupName)->with('creator')->firstOrFail();
        $group->checkAccess();

        $stats = [
            'contents' => (int) Content::where('group_id', $group->getKey())->count(),
            'comments' => (int) Content::where('group_id', $group->getKey())->sum('comments'),
            'entries' => (int) Entry::where('group_id', $group->getKey())->count(),
            'banned' => (int) GroupBan::where('group_id', $group->getKey())->count(),
            'subscribers' => $group->subscribers,
            'moderators' => (int) GroupModerator::where('group_id', $group->getKey())->count(),
        ];

        return array_merge(
            $group->toArray(),
            ['stats' => $stats]
        );
    }
}
