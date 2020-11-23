<?php

namespace App\Domain\Query;

use App\Infrastructure\TransferObject\DbResult;

interface FetchPostsQuery
{
    public function get(): DbResult;

    // @todo specification / strategy?
    public function getPaginated(int $offset, int $limit): DbResult;
}
