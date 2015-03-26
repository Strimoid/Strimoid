<?php namespace Strimoid\Models;

class UserSetting extends BaseModel
{
    protected $table = 'user_settings';
    public $timestamps = false;

    protected static $unguarded = true;
}
