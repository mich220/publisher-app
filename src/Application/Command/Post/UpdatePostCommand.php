<?php

namespace App\Application\Command\Post;

use App\Application\Message\PostMessage;
use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\PostTitle;
use App\Domain\ValueObject\TaskId;

class UpdatePostCommand implements PostMessage
{
    private ?PostId $postId;
    private PostTitle $postTitle;
    private PostContent $postContent;
    private TaskId $taskId;
    private string $accessKey;

    public function __construct(
        ?PostId $postId,
        PostTitle $postTitle,
        PostContent $postContent,
        TaskId $taskId,
        string $accessKey
    )
    {
        $this->postId = $postId;
        $this->postTitle = $postTitle;
        $this->postContent = $postContent;
        $this->taskId = $taskId;
        $this->accessKey = $accessKey;
    }

    public function getPostId(): ?PostId
    {
        return $this->postId;
    }

    public function getPostTitle(): PostTitle
    {
        return $this->postTitle;
    }

    public function getPostContent(): PostContent
    {
        return $this->postContent;
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
            'post_title' => $this->postTitle->getTitle(),
            'post_content' => $this->postContent->toString(),
            'task_id' => $this->taskId->toString(),
            'access_key' => $this->accessKey,
        ];
    }

    public static function deserialize(array $serialized): self
    {
        return new self(
            new PostId($serialized['post_id']),
            new PostTitle($serialized['post_title']),
            new PostContent($serialized['post_content']),
            new TaskId($serialized['task_id']),
            $serialized['access_key']
        );
    }
}
