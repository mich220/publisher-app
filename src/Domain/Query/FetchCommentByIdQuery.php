<?php

namespace App\Domain\Query;

use App\Domain\ValueObject\CommentId;
use App\Domain\ValueObject\PostId;
use App\UI\TransferObject\CommentsDTO;

interface FetchCommentByIdQuery
{
    public function get(CommentId $commentId, PostId $postId): CommentsDTO;
}