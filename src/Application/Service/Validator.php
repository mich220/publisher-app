<?php

namespace App\Application\Service;

use App\Application\Exception\ValidationException;
use App\Domain\Specification\Specification;
use Symfony\Component\HttpFoundation\Request;

class Validator
{
    private array $errors = [];
    /**
     * @var Specification []
     */
    private array $specifications = [];

    public function addSpecification(Specification $specification)
    {
        $this->specifications[] = $specification;
    }

    public function validate(Request $request)
    {
        /**
         * @var Specification $specification
         */
        foreach ($this->specifications as $specification) {
            if (!$specification->isSatisfiedBy($request)) {
                $this->addError('validationErrors'); // todo add ValidationResult and/or Validated
            }
        }

        if ($this->hasErrors()) {
            $validationException = new ValidationException();
            $validationException->setValidationErrors($this->getErrors());
            throw $validationException;
        }
    }

    private function addError(string $error)
    {
        $this->errors[] = $error;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}