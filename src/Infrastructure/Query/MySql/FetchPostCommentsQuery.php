<?php

namespace App\Infrastructure\Query\MySql;

use Doctrine\DBAL\Connection;
use App\Domain\ValueObject\PostId;
use App\Domain\Query\FetchPostCommentsQuery as FetchPostCommentsQueryInterface;
use App\Infrastructure\TransferObject\DbResult;

class FetchPostCommentsQuery implements FetchPostCommentsQueryInterface
{
    // @todo add dto

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(PostId $id): DbResult
    {
        $query = 'SELECT 
                    id as commentId, 
                    post_id as postId,
                    content as commentContent, 
                    created_at 
                  FROM 
                    comment 
                  WHERE 
                    post_id = :post_id';
        $result = $this->connection->executeQuery($query, [
            'post_id' => $id->toString()
        ])->fetchAllAssociative();



        return new DbResult($result, ['commentContent', 'created_at', 'commentId', 'postId']);
    }
}