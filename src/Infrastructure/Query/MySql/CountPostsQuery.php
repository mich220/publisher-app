<?php

namespace App\Infrastructure\Query\MySql;

use Doctrine\DBAL\Connection;
use App\Domain\Query\CountPostsQuery as CountPostsQueryInterface;

class CountPostsQuery implements CountPostsQueryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(): int
    {
        $query = 'SELECT count(1) as count FROM post';
        $result = $this->connection->fetchOne($query);

        return $result;
    }
}