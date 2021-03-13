<?php

namespace App\Shared\Exceptions;

use DomainException;

class BusinessLogicViolationException extends DomainException implements ExceptionInterface
{
}
