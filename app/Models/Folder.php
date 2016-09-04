<?php namespace Strimoid\Models;

use Auth;

class Folder extends BaseModel
{
    protected $table = 'folders';
    protected $visible = ['id', 'name', 'groups'];

    protected $attributes = [
        'groups' => [],
        'public' => false,
    ];

    protected static $rules = [
        'name' => 'required|min:1|max:64|regex:/^[a-z0-9\pL ]+$/u',
    ];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'folder_groups');
    }

    public function comments($sortBy = null)
    {
        $builder = with(new Comment())->newQuery();
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        $groups = $this->groups;
        $builder->whereIn('group_id', $groups);

        return $builder;
    }

    public function contents($tab = null, $sortBy = null)
    {
        $builder = with(new Content())->newQuery();

        $groups = $this->groups;
        $builder->whereIn('group_id', $groups);

        if ($tab == 'popular') {
            $builder->popular();
        }
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }

    public function entries()
    {
        $builder = with(new Entry())->newQuery();

        $groups = $this->groups;
        $builder->whereIn('group_id', $groups);

        return $builder;
    }

    public function canBrowse()
    {
        $isOwner = $this->user->getKey() === Auth::id();

        return $this->public || $isOwner;
    }
}
