<?php

namespace App\Domain\Policy;

use App\Domain\Entity\Comment;

class CommentPolicy
{
    public function canModify(Comment $comment, string $accessKey): bool
    {
        if ($comment->getAccessKey() === $accessKey) {
            return true;
        } else {
            return false;
        }
    }
}