<?php

namespace App\Domain\Policy;

use App\Domain\Entity\Post;

class PostPolicy
{
    public function canModify(Post $post, string $accessKey): bool
    {
        if ($post->getAccessKey() === $accessKey) {
            return true;
        } else {
            return false;
        }
    }
}