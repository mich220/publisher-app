<?php

namespace App\Infrastructure\Repository\MySql;

use App\Domain\Aggregates\PostAggregate;
use App\Domain\Entity\Comment;
use App\Domain\Repository\PostAggregateRepository as PostRepositoryInterface;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use App\Infrastructure\TransferObject\DbResult;
use Doctrine\DBAL\Driver\Connection;

class PostAggregateRepository implements PostRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function exists(PostId $postId): bool
    {
        $query = 'SELECT id FROM post WHERE id = :post_id LIMIT 1';

        $result = $this->connection->executeQuery($query, [
            'post_id' => $postId->toString()
        ])->fetchAllAssociative();

        return count($result) === 1;
    }

    public function save(PostAggregate $post): int
    {
        $post = $post->getPost();

        $query = 'INSERT INTO post (title, content, access_key) VALUES (:title, :content, :access_key)';
        $this->connection->executeQuery($query, [
            'title' => $post->getPostTitle()->toString(),
            'content' => $post->getPostContent()->toString(),
            'access_key' => $post->getAccessKey(),
        ]);

        return $this->getLastInsertedId();
    }

    public function update(PostAggregate $post): void
    {
        $postEntity = $post->getPost();

        $query = 'UPDATE post SET title = :post_title, content = :post_content WHERE id = :post_id';

        $this->connection->executeQuery($query, [
            'post_id' => $postEntity->getId()->toString(),
            'post_title' => $postEntity->getPostTitle()->toString(),
            'post_content' => $postEntity->getPostContent()->toString()
        ]);

        $this->updateComments($post);
    }

    public function load(PostId $postId, $withComments = false): DbResult
    {
        $query = '
            SELECT 
                p.id as postId,
                p.title as postTitle,
                p.access_key as accessKey,
                p.content as postContent
            FROM 
                post p 
            WHERE
                p.id = :post_id
            LIMIT 1
        ';

        $post = $this->connection->executeQuery($query, [
            'post_id' => $postId->toString()
        ])->fetchAssociative();

        $post = new DbResult($post, [
            'postId',
            'postTitle',
            'accessKey',
            'postContent',
        ]);

        if ($withComments) {
            $comments = $this->loadComments($postId);
            $post->setRelation('comments', $comments);
        }

        return $post;
    }

    public function loadComments(PostId $postId): DbResult
    {
        $query = '
            SELECT  
                c.id as commentId,
                c.post_id as postId,
                c.content as commentContent,
                c.access_key as commentAccessKey
            FROM
                comment c
            WHERE post_id = :post_id
        ';

        $result = $this->connection->executeQuery($query, [
            'post_id' => $postId->toString()
        ])->fetchAllAssociative();

        $result = new DbResult($result, [
            'commentId',
            'postId',
            'commentContent',
            'commentAccessKey'
        ]);

        return $result;
    }

    public function delete(PostId $postId): void
    {
        // cascade delete
        $query = 'DELETE FROM post WHERE id = :post_id';

        $this->connection->executeQuery($query, [
            'post_id' => $postId->toString()
        ]);
    }

    private function updateComments(PostAggregate $post): void
    {
        $postEntity = $post->getPost();

        /**
         * @var Comment $comment
         */
        foreach ($post->getComments() as $comment) {
            if (!$comment->getIsDirty()) {
                continue;
            }
            if ($comment->getId() === null) {
                $this->insertComment($comment, $postEntity->getId());
            } else {
                $this->updateComment($comment, $postEntity->getId());
            }
        }
    }

    private function updateComment(Comment $comment, PostId $postId): void
    {
        $query = 'UPDATE comment SET content = :comment_content WHERE post_id = :post_id AND id = :comment_id';
        $this->connection->executeQuery($query, [
            'post_id' => $postId->toString(),
            'comment_id' => $comment->getId()->toString(),
            'comment_content' => $comment->getCommentContent()->toString()
        ]);
    }

    private function insertComment(Comment $comment, PostId $postId): int
    {
        $query = 'INSERT INTO comment (post_id, content, access_key) VALUES (:post_id, :comment_content, :access_key)';

        $this->connection->executeQuery($query, [
            'post_id' => $postId->toString(),
            'comment_content' => $comment->getCommentContent()->toString(),
            'access_key' => $comment->getAccessKey()
        ]);
        $commentId = $this->getLastInsertedId();
        $comment->setId(new CommentId($commentId));

        return $commentId;
    }

    public function deleteComment(Comment $comment, PostId $postId): void
    {
        $query = 'DELETE FROM comment WHERE post_id = :post_id AND id = :comment_id';

        $this->connection->executeQuery($query, [
            'post_id' => $postId->toString(),
            'comment_id' => $comment->getId()->toString(),
        ]);
    }

    private function getLastInsertedId(): int
    {
        $query = 'SELECT LAST_INSERT_ID() as id';
        $result = $this->connection->fetchAllAssociative($query);

        return array_pop($result)['id'];
    }
}
