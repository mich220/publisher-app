<?php

namespace App\Domain\Collections;

class ParameterBag implements \Countable, \IteratorAggregate
{
    private array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function has($key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    public function get($key, $default = null)
    {
        return array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    public function count()
    {
        return $this->count();
    }
}
