<?php namespace Strimoid\OAuth; 

use League\OAuth2\Server\Storage\AccessTokenInterface;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\ScopeEntity;

class AccessTokenStorage extends MongoStorage implements AccessTokenInterface {

    protected $table = 'oauth_access_tokens';

    /**
     * Get an instance of Entity\AccessTokenEntity
     *
     * @param string $token The access token
     *
     * @return \League\OAuth2\Server\Entity\AccessTokenEntity
     */
    public function get($token)
    {
        $result = $this->table()
            ->where('_id', $token)
            ->first();

        if ( ! $result) return;

        return (new AccessTokenEntity($this->server))
            ->setId($result['_id'])
            ->setExpireTime($result['expire_time']);
    }

    /**
     * Get the scopes for an access token
     *
     * @param \League\OAuth2\Server\Entity\AccessTokenEntity $token The access token
     *
     * @return array Array of \League\OAuth2\Server\Entity\ScopeEntity
     */
    public function getScopes(AccessTokenEntity $token)
    {
        $result = $this->table()
            ->where('_id', $token->getId())
            ->first();

        if ( ! $result) return [];

        $scopes = [];

        foreach ($result['scopes'] as $scope)
        {
            $scopes[] = $this->server
                ->getScopeStorage()
                ->get($scope);
        }

        return $scopes;
    }

    /**
     * Creates a new access token
     *
     * @param string         $token      The access token
     * @param integer        $expireTime The expire time expressed as a unix timestamp
     * @param string|integer $sessionId  The session ID
     *
     * @return void
     */
    public function create($token, $expireTime, $sessionId)
    {
        $this->table()
            ->insert([
                '_id'           => $token,
                'expire_time'   => $expireTime,
                'session_id'    => $sessionId,
            ]);
    }

    /**
     * Associate a scope with an access token
     *
     * @param \League\OAuth2\Server\Entity\AccessTokenEntity $token The access token
     * @param \League\OAuth2\Server\Entity\ScopeEntity       $scope The scope
     *
     * @return void
     */
    public function associateScope(AccessTokenEntity $token, ScopeEntity $scope)
    {
        $this->table()
            ->where('_id', $token)
            ->push('scopes', $scope->getId());
    }

    /**
     * Delete an access token
     *
     * @param \League\OAuth2\Server\Entity\AccessTokenEntity $token The access token to delete
     *
     * @return void
     */
    public function delete(AccessTokenEntity $token)
    {
        $this->table()
            ->where('_id', $token)
            ->delete();
    }

}
