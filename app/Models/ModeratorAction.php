<?php namespace Strimoid\Models;

class ModeratorAction extends BaseModel
{
    protected $table = 'moderator_actions';

    const TYPE_MODERATOR_ADDED      = 1;
    const TYPE_MODERATOR_REMOVED    = 2;
    const TYPE_SETTINGS_CHANGED     = 3;
    const TYPE_STYLE_CHANGED        = 4;

    public function moderator()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function target()
    {
        return $this->belongsTo('Strimoid\Models\User');
    }

    public function group()
    {
        return $this->belongsTo('Strimoid\Models\Group');
    }
}
