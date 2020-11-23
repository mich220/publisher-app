<?php

namespace App\Infrastructure\Cache\Redis;

use Predis\Client;

class RedisClient
{
    private $redisUrl;
    private $expireTTL;
    private ?Client $client = null;

    public function __construct(string $redisUrl, int $expireTTL = null)
    {
        $this->redisUrl = $redisUrl;
        $this->expireTTL = $expireTTL;
    }

    public function getClient(): Client
    {
        if (!$this->client) {
            $this->client = new Client($this->redisUrl);
        }
        return $this->client;
    }

    public function getExpireTTL(): int
    {
        return $this->expireTTL;
    }
}