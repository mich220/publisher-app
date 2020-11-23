<?php

namespace App\Domain\Query;

use App\Domain\ValueObject\PostId;

interface FetchCommentsByPostIdQuery
{
    public function get(PostId $id): array;
}