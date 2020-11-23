<?php

namespace App\Application\Command\Post;

use App\Application\Message\PostMessage;
use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostTitle;
use App\Domain\ValueObject\TaskId;

class CreatePostCommand implements PostMessage
{
    private PostTitle $postTitle;
    private PostContent $postContent;
    private TaskId $taskId;
    private string $accessKey;

    public function __construct(PostTitle $postTitle, PostContent $postContent, TaskId $taskId, string $accessKey)
    {
        $this->postTitle = $postTitle;
        $this->postContent = $postContent;
        $this->taskId = $taskId;
        $this->accessKey = $accessKey;
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
            'post_title' => $this->postTitle->toString(),
            'post_content' => $this->postContent->toString(),
            'task_id' => $this->taskId->toString(),
            'access_key' => $this->accessKey,
        ];
    }

    public static function deserialize(array $serialized): self
    {
        return new self(
            new PostTitle($serialized['post_title']),
            new PostContent($serialized['post_content']),
            new TaskId($serialized['task_id']),
            $serialized['access_key'],
        );
    }
}
