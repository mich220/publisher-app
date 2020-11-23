<?php

namespace App\Application\Message;

interface CommentMessage
{
    public function serialize(): array;
    public static function deserialize(array $serialized): self;
}