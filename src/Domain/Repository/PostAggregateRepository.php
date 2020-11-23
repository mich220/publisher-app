<?php

namespace App\Domain\Repository;

use App\Domain\Aggregates\PostAggregate;
use App\Domain\ValueObject\PostId;
use App\Infrastructure\TransferObject\DbResult;

interface PostAggregateRepository
{
    public function save(PostAggregate $post): int;
    public function exists(PostId $postId): bool;
    public function update(PostAggregate $post): void;
    public function delete(PostId $postId): void;
    public function load(PostId $postId, bool $withComments = false): DbResult;
}