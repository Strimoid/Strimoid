<?php namespace Strimoid\OAuth; 

use League\OAuth2\Server\Storage\AuthCodeInterface;
use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;

class AuthCodeStorage extends MongoStorage implements AuthCodeInterface {

    protected $table = 'oauth_auth_codes';

    /**
     * Get the auth code
     *
     * @param string $code
     *
     * @return \League\OAuth2\Server\Entity\AuthCodeEntity
     */
    public function get($code)
    {
        $result = $this->table()
            ->where('_id', $code)
            ->where('expire_time', '>=', time())
            ->first();

        if ( ! $result) return;

        return (new AuthCodeEntity($this->server))
            ->setRedirectUri($result['client_redirect_uri'])
            ->setId($result['_id'])
            ->setExpireTime($result['expire_time']);
    }

    /**
     * Create an auth code.
     *
     * @param string $token The token ID
     * @param integer $expireTime Token expire time
     * @param integer $sessionId Session identifier
     * @param string $redirectUri Client redirect uri
     *
     * @return void
     */
    public function create($token, $expireTime, $sessionId, $redirectUri)
    {
        $this->table()
            ->insert([
                '_id'                 => $token,
                'client_redirect_uri' => $redirectUri,
                'session_id'          => $sessionId,
                'expire_time'         => $expireTime,
            ]);
    }

    /**
     * Get the scopes for an access token
     *
     * @param \League\OAuth2\Server\Entity\AuthCodeEntity $token The auth code
     *
     * @return array Array of \League\OAuth2\Server\Entity\ScopeEntity
     */
    public function getScopes(AuthCodeEntity $token)
    {
        $result = $this->table()
            ->where('_id', $token->getId())
            ->first();

        if (!$result) return [];

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
     * Associate a scope with an acess token
     *
     * @param \League\OAuth2\Server\Entity\AuthCodeEntity $token The auth code
     * @param \League\OAuth2\Server\Entity\ScopeEntity $scope The scope
     *
     * @return void
     */
    public function associateScope(AuthCodeEntity $token, ScopeEntity $scope)
    {
        $this->table()
            ->where('_id', $token->getId())
            ->push('scopes', $scope->getId());
    }

    /**
     * Delete an access token
     *
     * @param \League\OAuth2\Server\Entity\AuthCodeEntity $token The access token to delete
     *
     * @return void
     */
    public function delete(AuthCodeEntity $token)
    {
        $this->table()
            ->where('_id', $token->getId())
            ->delete();
    }

}
