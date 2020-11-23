<?php

namespace App\Domain\Query;

use App\Domain\ValueObject\PostId;
use App\Infrastructure\TransferObject\DbResult;
use App\UI\TransferObject\CommentsDTO;

interface FetchPostCommentsQuery
{
    public function get(PostId $id): DbResult;
}