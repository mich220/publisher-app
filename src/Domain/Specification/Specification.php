<?php

namespace App\Domain\Specification;

use Symfony\Component\HttpFoundation\Request;

interface Specification
{
    // @todo use fields instead of request
    public function isSatisfiedBy(object $request): bool;
}