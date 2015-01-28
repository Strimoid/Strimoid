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

}
