<?php

namespace App\Application\Message;

interface PostMessage
{
    public function serialize(): array;
    public static function deserialize(array $serialized): self;
}