<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Folder extends BaseModel
{

    protected static $unguarded = true;
    protected $visible = ['_id', 'name', 'groups'];

    public static function find($id, $columns = array('*')) {
        return static::findUserFolder(Auth::user()->_id, $id, $columns);
    }

    public static function findUserFolder($userId, $id, $columns = array('*')) {
        $parent = User::where('_id', $userId)
            //->where('_folders', 'elemMatch', ['_id' => $id])
            ->elemMatch('_folders', '_id', $id)
            ->first();

        if (!$parent)
        {
            return null;
        }

        return $parent->folders->first();
    }
    public static function findUserFolderOrFail($userId, $id, $columns = array('*'))
    {
        if ( ! is_null($model = static::findUserFolder($userId, $id, $columns))) return $model;

        throw new ModelNotFoundException;
    }

    public function contents()
    {
        $builder = with(new Content)->newQuery();

        $groups = $this->groups;
        $builder->whereIn('group_id', $groups);

        return $builder;
    }

    public function entries()
    {
        $builder = with(new Entry)->newQuery();

        $groups = $this->groups;
        $builder->whereIn('group_id', $groups);

        return $builder;
    }

    public function mpush($column, $value = null, $unique = false)
    {
        if (!$this->_id)
            return new Exception('Tried to push on model without id');

        $column = '_folders.$.'. $column;

        $builder = User::where('_id', $this->user->_id)->where('_folders._id', $this->_id);
        return $builder->push($column, $value, $unique);
    }

    public function mpull($column, $value = null)
    {
        if (!$this->_id)
            return new Exception('Tried to pull on model without id');

        $column = '_folders.$.'. $column;

        $builder = User::where('_id', $this->user->_id)->where('_folders._id', $this->_id);
        return $builder->pull($column, $value);
    }

}