<?php

namespace App\Domain\Query;

use App\Domain\ValueObject\PostId;
use App\Infrastructure\TransferObject\DbResult;

interface FetchPostByIdQuery
{
    public function get(PostId $id): DbResult;
}