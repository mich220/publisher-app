<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\CommentContent;
use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;

class Comment
{
    private ?CommentId $id;
    private PostId $postId;
    private CommentContent $commentContent;
    private string $accessKey;
    private bool $isDirty = false;

    public function __construct(?CommentId $id, PostId $postId, CommentContent $commentContent, string $accessKey)
    {
        $this->id = $id;
        $this->postId = $postId;
        $this->commentContent = $commentContent;
        $this->accessKey = $accessKey;
        if (!$id) {
            $this->isDirty = true;
        }
    }

    public function getId(): ?CommentId
    {
        return $this->id;
    }

    public function setId(CommentId $id): void
    {
        $this->id = $id;
    }

    public function getPostId(): PostId
    {
        return $this->postId;
    }

    public function getCommentContent(): CommentContent
    {
        return $this->commentContent;
    }

    public function setIsDirty(bool $isDirty)
    {
        $this->isDirty = $isDirty;
    }

    public function getIsDirty(): bool
    {
        return $this->isDirty;
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    // @todo factory?
    public static function create(?CommentId $id, PostId $post_id, CommentContent $commentContent, string $accessKey): self
    {
        return new self($id, $post_id, $commentContent, $accessKey);
    }

    public static function createFromArray(array $comment): self
    {
        return new self(
            new CommentId($comment['commentId']),
            new PostId($comment['postId']),
            new CommentContent($comment['commentContent']),
            $comment['commentAccessKey'] ?? ''
        );
    }

    public function serialize(): array
    {
        return [
            'id' => isset($this->id) ? $this->id->toString() : null,
            'content' => $this->commentContent->toString(),
            'post_id' => $this->postId->toString(),
        ];
    }

    public function deserialize(array $serialized): self
    {
        return new self('', '', '', '');
    }
}
