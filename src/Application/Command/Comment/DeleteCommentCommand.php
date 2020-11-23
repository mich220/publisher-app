<?php

namespace App\Application\Command\Comment;

use App\Application\Message\CommentMessage;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\TaskId;

class DeleteCommentCommand implements CommentMessage
{
    private PostId $postId;
    private CommentId $commentId;
    private TaskId $taskId;
    private string $accessKey;

    public function __construct(PostId $postId, CommentId $commentId, TaskId $taskId, string $accessKey)
    {
        $this->postId = $postId;
        $this->commentId = $commentId;
        $this->taskId = $taskId;
        $this->accessKey = $accessKey;
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

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    public function serialize(): array
    {
        return [
            'post_id' => $this->postId->toString(),
            'comment_id' => $this->commentId->toString(),
            'task_id' => $this->taskId->toString(),
            'access_key' => $this->accessKey,
        ];
    }

    public static function deserialize(array $serialized): self
    {
        return new self(
            new PostId($serialized['post_id']),
            new CommentId($serialized['comment_id']),
            new TaskId($serialized['task_id']),
            $serialized['access_key']
        );
    }
}
