<?php

namespace App\Application\Proxy;

use App\Domain\Repository\PostAggregateRepository;
use App\Domain\Query\FetchPostByIdQuery;
use App\Domain\ValueObject\PostId;
use App\Infrastructure\TransferObject\DbResult;

class FetchPostByIdQueryProxy implements FetchPostByIdQuery
{
    private PostAggregateRepository $postCacheRepository;
    private FetchPostByIdQuery $query;

    public function __construct(PostAggregateRepository $postCacheRepository, FetchPostByIdQuery $query)
    {
        $this->postCacheRepository = $postCacheRepository;
        $this->query = $query;
    }
    public function get(PostId $id): DbResult
    {
        if ($this->postCacheRepository->exists($id)) {
            return $this->postCacheRepository->load($id);
        } else {
            return $this->query->get($id);
        }
    }
}