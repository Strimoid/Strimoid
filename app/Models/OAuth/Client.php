<?php namespace Strimoid\Models\OAuth;

use Strimoid\Models\BaseModel;
use Strimoid\Models\Traits\HasUserRelationship;

class Client extends BaseModel
{
    use HasUserRelationship;

    protected $table = 'oauth_clients';
}
