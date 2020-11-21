<?php

namespace App\Exceptions;

use App\Exceptions\Interfaces\ExceptionInterface;
use Throwable;

class ApiValidationException extends \DomainException implements ExceptionInterface
{
    private array $validationErrors = [];


    public function __construct($message = '', $code = 0, Throwable $previous = null, array $errors = []) {
        parent::__construct($message, $code, $previous);
        $this->validationErrors = $errors;
    }

    public function getValidationErrors(): array
    {
        return$this->validationErrors;
    }
}
