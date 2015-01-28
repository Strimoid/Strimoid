<?php namespace Strimoid\OAuth;

use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Storage\AbstractStorage;
use League\OAuth2\Server\Storage\ScopeInterface;

class ScopeStorage extends AbstractStorage implements ScopeInterface {

    protected $scopes = [
        //
    ];

    /**
     * Return information about a scope
     *
     * @param string $scope     The scope
     * @param string $grantType The grant type used in the request (default = "null")
     * @param string $clientId  The client sending the request (default = "null")
     *
     * @return \League\OAuth2\Server\Entity\ScopeEntity
     */
    public function get($scope, $grantType = null, $clientId = null)
    {
        if (!array_key_exists($scope, $this->scopes)) return;

        return (new ScopeEntity($this->server))->hydrate([
            'id'            =>  $scope,
            'description'   =>  $this->scopes[$scope],
        ]);
    }

}
