<?php namespace Strimoid\Models\OAuth;

use duxet\Rethinkdb\Eloquent\Model;

class Client extends Model {

    protected $table = 'oauth_clients';

    public function user()
    {
        return $this->belongsTo('User');
    }

}
