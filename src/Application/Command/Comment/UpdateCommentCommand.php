<?php

namespace App\Application\Command\Comment;

use App\Application\Message\CommentMessage;
use App\Domain\ValueObject\CommentContent;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\TaskId;

class UpdateCommentCommand implements CommentMessage
{
    private PostId $postId;
    private CommentId $commentId;
    private CommentContent $commentContent;
    private TaskId $taskId;
    private string $accessKey;

    public function __construct(
        PostId $postId,
        CommentId $commentId,
        CommentContent $commentContent,
        TaskId $taskId,
        string $accessKey
    )
    {
        $this->postId = $postId;
        $this->commentId = $commentId;
        $this->commentContent = $commentContent;
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

    public function getCommentContent(): CommentContent
    {
        return $this->commentContent;
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
            'comment_content' => $this->commentContent->toString(),
            'task_id' => $this->taskId->toString(),
            'access_key' => $this->accessKey
        ];
    }

    public static function deserialize(array $serialized): self
    {
        return new self(
            new PostId($serialized['post_id']),
            new CommentId($serialized['comment_id']),
            new CommentContent($serialized['comment_content']),
            new TaskId($serialized['task_id']),
            $serialized['access_key']
        );
    }
}
