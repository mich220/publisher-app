<?php

namespace App\Infrastructure\Cache\Redis;

use \App\Domain\Cache\ResourceRepository as ResourceRepositoryInterface;

class ResourceRepository implements ResourceRepositoryInterface
{
    private RedisClient $client;

    public function __construct(RedisClient $client)
    {
        $this->client = $client;
    }

    public function get(string $resourceId): ?array
    {
        if ($this->client->getClient()->exists($resourceId)) {
            $data = $this->client->getClient()->get($resourceId);
            return json_decode($data, true);
        } else {
            return null;
        }
    }

    public function set(string $resourceId, array $data): void
    {
        $this->client->getClient()->set($resourceId, json_encode($data));
    }

    public function setEx(string $resourceId, array $data, int $ttl = null): void
    {
        if ($ttl === null) {
            $ttl = $this->client->getExpireTTL();
        }

        $this->client->getClient()->setex($resourceId, $ttl, json_encode($data));
    }
}