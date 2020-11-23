<?php

namespace App\Infrastructure\Query\MySql;

use App\Domain\ValueObject\PostId;
use Doctrine\DBAL\Connection;
use App\Domain\Query\FetchCommentsByPostIdQuery as FetchCommentsByPostIdQueryInterface;

class FetchCommentsByPostIdQuery implements FetchCommentsByPostIdQueryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(PostId $postId): array
    {
        $query = 'SELECT content, created_at FROM comment WHERE post_id = :post_id';
        $result = $this->connection->executeQuery($query, [
            'post_id' => $postId,
        ])->fetchAllAssociative();

        return $result;
    }
}