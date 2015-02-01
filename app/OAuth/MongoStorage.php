<?php namespace Strimoid\OAuth;

use Illuminate\Database\ConnectionInterface;
use League\OAuth2\Server\Storage\AbstractStorage;

class MongoStorage extends AbstractStorage {

    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Use given table.
     *
     * @param string $table
     * @return \Illuminate\Database\Query\Builder
     */
    protected function table($table = null)
    {
        $table = $table ?: $this->table;
        return $this->connection->table($table);
    }

    /**
     * Turn array of scope names into array of scope entities.
     *
     * @param  $array
     * @return array
     */
    protected function loadScopes($array)
    {
        $scopes = [];

        foreach ($array as $scope)
        {
            $scopes[] = $this->server
                ->getScopeStorage()
                ->get($scope);
        }

        return $scopes;
    }

}
