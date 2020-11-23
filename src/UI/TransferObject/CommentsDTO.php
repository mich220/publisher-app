<?php

namespace App\UI\TransferObject;

use App\Domain\Collections\CommentsCollection;
use App\Domain\Entity\Comment;

class CommentsDTO
{
    public CommentsCollection $comments;

    public function toArray()
    {
        $comments = [];
        /**
         * @var Comment []
         */
        foreach ($this->comments->getComments() as $comment) {
            $comments[] = [
                'id' => $comment->getId()->toString(),
                'postId' => $comment->getPostId()->toString(),
                'content' => $comment->getCommentContent()->toString(),
            ];
        }

        return $comments;
    }
}