<?php

namespace App\Domain\Aggregates;

use App\Application\Exception\AuthorizationException;
use App\Application\Exception\CommentNotFoundException;
use App\Domain\Collections\CommentsCollection;
use App\Domain\Entity\Comment;
use App\Domain\Entity\Post;
use App\Domain\Policy\CommentPolicy;
use App\Domain\Policy\PostPolicy;

class PostAggregate
{
    // @todo add state + event sourcing
    private Post $post;
    private CommentsCollection $commentsCollection;

    public function __construct(Post $post, CommentsCollection $commentsCollection)
    {
        $this->post = $post;
        $this->commentsCollection = $commentsCollection;
    }

    public function update(Post $post, PostPolicy $policy): void
    {
        $oldPostId = $this->post->getId()->toString();
        $newPostId = $post->getId()->toString();

        if ($oldPostId !== $newPostId) {
            throw new \InvalidArgumentException();
        }

        if (!$policy->canModify($this->post, $post->getAccessKey())) {
            throw new AuthorizationException();
        }

        $this->post = new Post(
            $this->post->getId(),
            $post->getPostTitle(),
            $post->getPostContent(),
            $post->getAccessKey()
        );
    }
    
    public function publishComment(Comment $comment)
    {
        $this->commentsCollection->add($comment);
    }

    public function updateComment(Comment $comment, CommentPolicy $policy)
    {
        $oldComment = $this->commentsCollection->find($comment);

        if ($oldComment === null) {
            throw new CommentNotFoundException();
        }

        if (!$policy->canModify($oldComment, $comment->getAccessKey())) {
            throw new AuthorizationException();
        }

        $this->commentsCollection->update($comment);
    }

    public function archive(Comment $comment, CommentPolicy $policy)
    {
        $oldComment = $this->commentsCollection->find($comment);

        if ($oldComment === null) {
            throw new CommentNotFoundException();
        }

        if (!$policy->canModify($oldComment, $comment->getAccessKey())) {
            throw new AuthorizationException();
        }

        // todo archive?
        $this->commentsCollection->delete($comment);
    }

    // getters -> because we cannot have public 'read only' attributes
    public function getPost(): Post
    {
        return $this->post;
    }

    public function getComments(): CommentsCollection
    {
        return $this->commentsCollection;
    }
}