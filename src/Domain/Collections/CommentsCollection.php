<?php

namespace App\Domain\Collections;

use App\Application\Exception\CommentNotFoundException;
use App\Domain\Collections\Iterators\CommentsCollectionIterator;
use App\Domain\Entity\Comment;
use App\Infrastructure\TransferObject\DbResult;
use Traversable;

class CommentsCollection implements \Countable, \IteratorAggregate
{
    /**
     * @var Comment[]
     */
    private array $comments = [];

    public function add(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    public function find(Comment $comment): ?Comment
    {
        $comments = $this->comments;

        $result = array_filter($comments, function(Comment $inArrayComment) use ($comment) {
            return $inArrayComment->getId()->toString() === $comment->getId()->toString();
        });

        return array_pop($result);
    }

    public function update(Comment $comment)
    {
        $newComments = [];
        foreach ($this->comments as $key => $oldCollectionComment) {
            if ($oldCollectionComment->getId()->toString() === $comment->getId()->toString()) {
                $comment->setIsDirty(true);
                $newComments[$key] = $comment;
            } else {
                $newComments[$key] = $this->comments[$key];
            }
        }

        $this->comments = $newComments;
    }

    public function delete(Comment $comment): void
    {
        $newComments = [];
        foreach ($this->comments as $key => $oldCollectionComment) {
            if ($oldCollectionComment->getId()->toString() === $comment->getId()->toString()) {
                unset($this->comments[$key]);
            } else {
                $newComments[] = $oldCollectionComment;
            }
        }

        $this->comments = $newComments;
    }

    public function count(): int
    {
        return count($this->comments);
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        return $this->comments;
    }

    /**
     * @return CommentsCollectionIterator|Traversable
     */
    public function getIterator()
    {
        return new CommentsCollectionIterator($this);
    }

    public static function createFromDbResult(DbResult $result): self
    {
        $commentsCollection = new CommentsCollection();

        foreach ($result->getData() as $comment) {
            if ($comment['commentId'] === null) {
                break;
            }

            $commentsCollection->add(Comment::createFromArray($comment));
        }

        return $commentsCollection;
    }

    public function serialize(): array
    {
        $comments = [];
        foreach ($this->comments as $comment) {
            $comments[] = $comment->serialize();
        }

        return $comments;
    }
}