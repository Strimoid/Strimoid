<?php namespace Strimoid\OAuth; 

use League\OAuth2\Server\Entity\ClientEntity;
use League\OAuth2\Server\Storage\ClientInterface;
use League\OAuth2\Server\Entity\SessionEntity;

class ClientStorage extends MongoStorage implements ClientInterface {

    protected $table = 'oauth_clients';

    /**
     * Validate a client
     *
     * @param string $clientId     The client's ID
     * @param string $clientSecret The client's secret (default = "null")
     * @param string $redirectUri  The client's redirect URI (default = "null")
     * @param string $grantType    The grant type used (default = "null")
     *
     * @return \League\OAuth2\Server\Entity\ClientEntity
     */
    public function get($clientId, $clientSecret = null, $redirectUri = null, $grantType = null)
    {
        $query = $this->table()
            ->where('_id', $clientId);

        if ($clientSecret)
        {
            $query->where('secret', $clientSecret);
        }

        if ($redirectUri)
        {
            $query->where('redirect_uri', $redirectUri);
        }

        $result = $query->first();

        if (!$result) return;

        return $this->rowToEntity($result);
    }

    /**
     * Get the client associated with a session
     *
     * @param \League\OAuth2\Server\Entity\SessionEntity $session The session
     *
     * @return \League\OAuth2\Server\Entity\ClientEntity
     */
    public function getBySession(SessionEntity $session)
    {
        $session = $this->table('oauth_sessions')
            ->where('_id', $session->getId())
            ->first();

        if (!$session) return;

        $result = $this->table()
            ->where('_id', $session['client_id'])
            ->first();

        if (!$result) return;

        return $this->rowToEntity($result);
    }

    protected function rowToEntity($row)
    {
        return (new ClientEntity($this->server))
            ->hydrate([
                'id'    => $row['_id'],
                'name'  => $row['name'],
            ]);
    }

}
