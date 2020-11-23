<?php

namespace App\Infrastructure\Query\MySql;

use Doctrine\DBAL\Connection;
use App\Domain\Query\FetchPostsQuery as FetchPostsQueryInterface;
use App\Infrastructure\TransferObject\DbResult;

class FetchPostsQuery implements FetchPostsQueryInterface
{
    // @todo add dto + specification

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(): DbResult
    {
        $query = '
                SELECT   
                    id as postId,
                    title as postTitle,
                    content as postContent,
                    created_at  
                FROM 
                    post';

        $result = $this->connection->fetchAllAssociative($query);

        return new DbResult($result, ['postId', 'postTitle', 'postContent', 'created_at']);
    }

    public function getPaginated(int $page, int $limit): DbResult
    {
        $query = $this->connection->prepare('
                SELECT
                    id as postId,
                    title as postTitle,
                    content as postContent,
                    created_at 
                FROM 
                    post LIMIT :offset, :limit');

        $offset = $page * $limit;
        $query->bindValue('offset', $offset, \PDO::PARAM_INT);
        $query->bindValue('limit', $limit, \PDO::PARAM_INT);

        $query->execute();

        $result = $query->fetchAllAssociative();

        return new DbResult($result, ['postId', 'postTitle', 'postContent', 'created_at']);
    }
}
