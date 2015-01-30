<?php namespace Strimoid\OAuth;

use League\OAuth2\Server\Storage\SessionInterface;
use League\OAuth2\Server\Entity\AccessTokenEntity;
use League\OAuth2\Server\Entity\AuthCodeEntity;
use League\OAuth2\Server\Entity\ScopeEntity;
use League\OAuth2\Server\Entity\SessionEntity;

class SessionStorage extends MongoStorage implements SessionInterface {

    protected $table = 'oauth_sessions';

    /**
     * Get a session from an access token
     *
     * @param \League\OAuth2\Server\Entity\AccessTokenEntity $accessToken The access token
     *
     * @return \League\OAuth2\Server\Entity\SessionEntity
     */
    public function getByAccessToken(AccessTokenEntity $accessToken)
    {
        $accessToken = $this->table('oauth_access_tokens')
            ->where('_id', $accessToken->getId())
            ->first();

        if ( ! $accessToken) return;

        return $this->getById($accessToken['session_id']);
    }

    /**
     * Get a session from an auth code
     *
     * @param \League\OAuth2\Server\Entity\AuthCodeEntity $authCode The auth code
     *
     * @return \League\OAuth2\Server\Entity\SessionEntity
     */
    public function getByAuthCode(AuthCodeEntity $authCode)
    {
        $authCode = $this->table('oauth_auth_codes')
            ->where('_id', $authCode->getId())
            ->first();

        if ( ! $authCode) return;

        return $this->getById($authCode['session_id']);
    }

    /**
     * Get a session from id
     *
     * @param $id
     *
     * @return \League\OAuth2\Server\Entity\SessionEntity
     */
    protected function getById($id)
    {
        $result = $this->table()
            ->where('_id', $id)
            ->first();

        if ( ! $result) return;

        return (new SessionEntity($this->server))
            ->setId($result['_id'])
            ->setOwner($result['owner_type'], $result['owner_id']);
    }

    /**
     * Get a session's scopes
     *
     * @param  \League\OAuth2\Server\Entity\SessionEntity
     *
     * @return array Array of \League\OAuth2\Server\Entity\ScopeEntity
     */
    public function getScopes(SessionEntity $session)
    {
        $result = $this->table()
            ->where('_id', $session->getId())
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
     * Create a new session
     *
     * @param string $ownerType         Session owner's type (user, client)
     * @param string $ownerId           Session owner's ID
     * @param string $clientId          Client ID
     * @param string $clientRedirectUri Client redirect URI (default = null)
     *
     * @return integer The session's ID
     */
    public function create($ownerType, $ownerId, $clientId, $clientRedirectUri = null)
    {
        return $this->table()
            ->insertGetId([
                'owner_type'  =>  $ownerType,
                'owner_id'    =>  $ownerId,
                'client_id'   =>  $clientId,
            ]);
    }

    /**
     * Associate a scope with a session
     *
     * @param \League\OAuth2\Server\Entity\SessionEntity $session The session
     * @param \League\OAuth2\Server\Entity\ScopeEntity   $scope   The scope
     *
     * @return void
     */
    public function associateScope(SessionEntity $session, ScopeEntity $scope)
    {
        $this->table()
            ->where('_id', $session->getId())
            ->push('scopes', $scope->getId());
    }

}
