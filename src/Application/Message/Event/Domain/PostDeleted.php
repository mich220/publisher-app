<?php

namespace App\Application\Message\Event\Domain;

use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\TaskId;

class PostDeleted implements DomainEvent
{
    private PostId $postId;
    private TaskId $taskId;

    public function __construct(PostId $postId, TaskId $taskId)
    {
        $this->postId = $postId;
        $this->taskId = $taskId;
    }
    public function getTaskId(): TaskId
    {
        return $this->taskId;
    }

    public function serialize(): array
    {
        return [
            'post_id' => $this->postId->toString(),
            'task_id' => $this->taskId->toString(),
        ];
    }

    public static function deserialize(array $serialized): self
    {
        return new self(new PostId($serialized['post_id']), new TaskId($serialized['task_id']));
    }
}