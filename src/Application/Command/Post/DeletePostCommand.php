<?php

namespace App\Application\Command\Post;

use App\Application\Message\PostMessage;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\TaskId;

class DeletePostCommand implements PostMessage
{
    private PostId $postId;
    private TaskId $taskId;
    private string $accessKey;

    public function __construct(PostId $postId, TaskId $taskId, string $accessKey)
    {
        $this->postId = $postId;
        $this->taskId = $taskId;
        $this->accessKey = $accessKey;
    }

    public function getPostId(): PostId
    {
        return $this->postId;
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
            'access_key' => $this->accessKey,
        ];
    }

    public static function deserialize(array $serialized): self
    {
        return new self(
            new PostId($serialized['post_id']),
            new TaskId($serialized['task_id']),
            $serialized['access_key']
        );
    }
}
