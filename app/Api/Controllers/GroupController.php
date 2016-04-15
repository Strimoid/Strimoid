<?php namespace Strimoid\Api\Controllers;

use Input;
use Strimoid\Models\Content;
use Strimoid\Models\Entry;
use Strimoid\Models\Group;
use Strimoid\Models\GroupBan;
use Strimoid\Models\GroupModerator;

class GroupController extends BaseController
{
    public function index()
    {
        $builder = Group::where('type', '!=', 'private');

        if (Input::has('name')) {
            $builder->where('name', 'like', '%'.Input::get('name').'%');
        }

        if (in_array(Input::get('sort'), ['created_at', 'subscribers_count'])) {
            $builder->orderBy(Input::get('sort'), 'desc');
        } else {
            $builder->orderBy('created_at', 'desc');
        }

        $groups = $builder->paginate(100);

        return $groups;
    }

    public function show($groupName)
    {
        $group = Group::name($groupName)->with('creator')->firstOrFail();
        $group->checkAccess();

        $stats = [
            'contents'    => intval(Content::where('group_id', $group->getKey())->count()),
            'comments'    => intval(Content::where('group_id', $group->getKey())->sum('comments')),
            'entries'     => intval(Entry::where('group_id', $group->getKey())->count()),
            'banned'      => intval(GroupBan::where('group_id', $group->getKey())->count()),
            'subscribers' => $group->subscribers,
            'moderators'  => intval(GroupModerator::where('group_id', $group->getKey())->count()),
        ];

        return array_merge(
            $group->toArray(),
            ['stats' => $stats]
        );
    }
}
