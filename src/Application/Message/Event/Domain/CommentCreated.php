<?php

namespace App\Application\Message\Event\Domain;

use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\TaskId;

class CommentCreated implements DomainEvent
{
    private PostId $postId;
    private CommentId $commentId;
    private TaskId $taskId;

    public function __construct(PostId $postId, CommentId $commentId, TaskId $taskId)
    {
        $this->postId = $postId;
        $this->commentId = $commentId;
        $this->taskId = $taskId;
    }

    public function getPostId(): PostId
    {
        return $this->postId;
    }

    public function getCommentId(): CommentId
    {
        return $this->commentId;
    }

    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function serialize(): array
    {
        return [
            'post_id' => $this->postId->toString(),
            'comment_id' => $this->commentId->toString(),
            'task_id' => $this->taskId->toString(),
        ];
    }

    public static function deserialize(array $serialized): self
    {
        return new self(new PostId($serialized['post_id']), new CommentId($serialized['comment_id']), new TaskId($serialized['task_id']));
    }
}