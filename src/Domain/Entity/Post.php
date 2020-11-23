<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\PostContent;
use App\Domain\ValueObject\PostId;
use App\Domain\ValueObject\PostTitle;

class Post
{
    private ?PostId $id;
    private PostTitle $postTitle;
    private PostContent $postContent;
    private string $accessKey;
    private string $status; //@todo add status class OR aggregate state

    public function __construct(?PostId $id, PostTitle $postTitle, PostContent $postContent, string $accessKey)
    {
        $this->id = $id;
        $this->postTitle = $postTitle;
        $this->postContent = $postContent;
        $this->accessKey = $accessKey;
    }

    public function getId(): ?PostId
    {
        return $this->id;
    }

    /**
     * @deprecated
     * use getId()
     */
    public function getPostId(): ?PostId
    {
        return $this->id;
    }

    public function getPostTitle(): PostTitle
    {
        return $this->postTitle;
    }

    public function getPostContent(): PostContent
    {
        return $this->postContent;
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    // @todo factory?
    public static function create(?PostId $id, PostTitle $postTitle, PostContent $postContent, string $accessKey)
    {
        return new self(
            $id,
            $postTitle,
            $postContent,
            $accessKey,
        );
    }

    public static function createFromArray(array $post)
    {
        return new self(
            new PostId($post['postId']),
            new PostTitle($post['postTitle']),
            new PostContent($post['postContent']),
            $post['accessKey'] ?? ''
        );
    }

    // todo add serializer?
    public function serialize(): array
    {
        return [
            'postId' => $this->id->toString(),
            'postTitle' => $this->id->toString(),
            'postContent' => $this->id->toString(),
        ];
    }
}