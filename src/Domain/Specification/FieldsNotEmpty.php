<?php

namespace App\Domain\Specification;

use Symfony\Component\HttpFoundation\Request;

class FieldsNotEmpty implements Specification
{
    const VALIDATION_ERROR = 'Fields are empty';

    private array $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function isSatisfiedBy(object $request): bool
    {
        foreach ($this->fields as $field) {
            if (!($request->get($field) === '' || $request->query->get($field) !== '')) {
                return false;
            }
        }

        return true;
    }
}