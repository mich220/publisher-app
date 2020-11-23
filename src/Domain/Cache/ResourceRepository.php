<?php

namespace App\Domain\Cache;

interface ResourceRepository
{
    public function get(string $resourceId): ?array;
    public function set(string $resourceId, array $data): void;
    public function setEx(string $resourceId, array $data, int $ttl = null): void;
}