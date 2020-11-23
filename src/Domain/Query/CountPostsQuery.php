<?php

namespace App\Domain\Query;

interface CountPostsQuery
{
    public function get(): int;
}