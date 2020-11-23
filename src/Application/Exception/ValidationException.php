<?php


namespace App\Application\Exception;


use Throwable;

class ValidationException extends \Exception
{
    private array $validationErrors;

    public function __construct($message = "validation exception", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setValidationErrors(array $errors)
    {
        $this->validationErrors = $errors;
    }

    public function getValidationErrors(): array
    {
        return $this->validationErrors ?? [];
    }
}
