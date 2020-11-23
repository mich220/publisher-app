<?php

namespace App\Infrastructure\Query\MySql;

use App\Domain\Collections\CommentsCollection;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use App\Infrastructure\TransferObject\DbResult;
use App\UI\TransferObject\CommentsDTO;
use Doctrine\DBAL\Connection;
use App\Domain\Query\FetchCommentByIdQuery as FetchCommentByIdQueryInterface;

class FetchCommentByIdQuery implements FetchCommentByIdQueryInterface
{
    // @todo add dto

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function get(CommentId $commentId, PostId $postId): CommentsDTO
    {
        $query = 'SELECT 
                    id as commentId,
                    content as commentContent,
                    post_id as postId,
                    created_at as createdAt
                FROM 
                    comment
                WHERE
                    post_id = :post_id
                AND 
                    id = :comment_id
        ';
        $result = $this->connection->fetchAllAssociative($query, [
            'post_id' => $postId->toString(),
            'comment_id' => $commentId->toString()
        ]);

        // @todo map to dto using db result?
        $dto = new CommentsDTO();
        $dto->comments = CommentsCollection::createFromDbResult(new DbResult($result, ['commentId', 'commentContent', 'postId', 'createdAt']));

        return $dto;
    }
}