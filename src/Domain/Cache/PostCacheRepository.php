<?php

namespace App\Domain\Cache;

use App\Domain\Entity\Post;
use App\Domain\ValueObject\PostId;
use App\UI\TransferObject\PostDTO;

interface PostCacheRepository
{
    public function hit(PostId $postId): bool;
    public function get(PostId $postId): PostDTO;
    public function set(Post $post): void;
}
