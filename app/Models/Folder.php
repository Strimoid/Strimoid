<?php

namespace Strimoid\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Folder extends BaseModel
{
    protected $table = 'folders';
    protected $fillable = ['name'];
    protected $visible = ['id', 'name', 'groups'];

    protected $attributes = [
        'public' => false,
    ];

    protected static array $rules = [
        'name' => 'required|min:1|max:64|regex:/^[a-z0-9\pL ]+$/u',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'folder_groups');
    }

    public function comments($sortBy = null)
    {
        $builder = with(new Comment())->newQuery();
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        $groupIds = $this->groups->pluck('id');
        $builder->whereIn('group_id', $groupIds);

        return $builder;
    }

    public function contents($tab = null, $sortBy = null)
    {
        $builder = with(new Content())->newQuery();

        $groupIds = $this->groups->pluck('id');
        $builder->whereIn('group_id', $groupIds);

        if ($tab == 'popular') {
            $builder->popular();
        }
        $builder->orderBy($sortBy ?: 'created_at', 'desc');

        return $builder;
    }

    public function entries()
    {
        $builder = with(new Entry())->newQuery();

        $groupIds = $this->groups->pluck('id');
        $builder->whereIn('group_id', $groupIds);

        return $builder;
    }

    public function canBrowse()
    {
        $isOwner = $this->user->getKey() === Auth::id();

        return $this->public || $isOwner;
    }
}
