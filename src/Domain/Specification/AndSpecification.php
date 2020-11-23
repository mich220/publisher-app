<?php

namespace App\Domain\Specification;

use Symfony\Component\HttpFoundation\Request;

class AndSpecification implements Specification
{
    private array $specifications;

    public function __construct(Specification ...$specifications)
    {
        $this->specifications = $specifications;
    }
    public function isSatisfiedBy(object $request): bool
    {
        foreach ($this->specifications as $specification) {
            if (!$specification->isSatisfiedBy($request)) {
                return false;
            }
        }

        return true;
    }
}