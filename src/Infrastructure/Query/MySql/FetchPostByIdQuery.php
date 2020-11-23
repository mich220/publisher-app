<?php

namespace App\Infrastructure\Query\MySql;

use Doctrine\DBAL\Connection;
use App\Domain\ValueObject\PostId;
use App\Domain\Query\FetchPostByIdQuery as FetchPostByIdQueryInterface;
use App\Infrastructure\TransferObject\DbResult;

class FetchPostByIdQuery implements FetchPostByIdQueryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // todo add specification
    public function get(PostId $id): DbResult
    {
        $query = '
            SELECT 
                p.id as postId,
                p.title as postTitle,
                p.content as postContent,
                p.created_at as createdAt
            FROM 
                post p 
            WHERE p.id = :post_id
        ';

        $result = $this->connection->executeQuery($query, [
            'post_id' => $id->toString()
        ])->fetchAllAssociative();

        return new DbResult($result, [
            'postId',
            'postTitle',
            'postContent',
            'createdAt',
        ]);
    }
}