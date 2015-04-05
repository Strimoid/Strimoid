<?php namespace Strimoid\Models\OAuth;

use Strimoid\Models\BaseModel;
use Strimoid\Models\Traits\HasUserRelationship;

/**
 * Strimoid\Models\OAuth\Client
 *
 * @property-read mixed $vote_state 
 * @property-read \Illuminate\Database\Eloquent\Collection|Vote[] $vote 
 * @property-read \Illuminate\Database\Eloquent\Collection|Save[] $usave 
 * @property-read User $user 
 * @method static \Strimoid\Models\BaseModel fromDaysAgo($days)
 */
class Client extends BaseModel
{
    use HasUserRelationship;

    protected $table = 'oauth_clients';
}
