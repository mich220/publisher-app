<?php

namespace App\Domain\Specification;

use Symfony\Component\HttpFoundation\Request;

class HasFields implements Specification
{
    const VALIDATION_ERROR = 'Missing fields';

    private array $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function isSatisfiedBy(object $request): bool
    {
        foreach ($this->fields as $field) {
            if (!($request->get($field) !== null || $request->query->get($field) !== null)) {
                return false;
            }
        }

        return true;
    }
}