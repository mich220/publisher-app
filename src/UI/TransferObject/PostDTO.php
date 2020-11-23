<?php

namespace App\UI\TransferObject;

use App\Domain\Collections\CommentsCollection;
use App\Domain\Entity\Post;

class PostDTO
{
    public Post $post;
    public CommentsCollection $commentsCollection;

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getCommentsCollection(): CommentsCollection
    {
        return $this->commentsCollection;
    }

    public static function deserialize(array $serialized): self
    {
        $post = $serialized['post'];
        $comments = $post['comments'];
        $dto = new self();
        $dto->post = Post::createFromArray($post);
        $dto->commentsCollection = CommentsCollection::createFromDbResult($comments);

        return $dto;
    }

    public function serialize()
    {
        $post = $this->post;

        $id = $post->getId()->toString();
        $title = $post->getPostTitle()->toString();
        $content = $post->getPostContent()->toString();

        $comments = isset($this->commentsCollection) ? $this->commentsCollection : new CommentsCollection();

        return [
            'post' => [
                'id' => $id,
                'shortTitle' => $title,
                'title' => $title,
                'shortContent' => $content,
                'content' => $content,
                'comments' => $comments->serialize()
            ],
        ];
    }
}