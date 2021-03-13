<?php

namespace App\Shared\Exceptions;

use DomainException;

class ResetTokenAlreadyRequestedException extends DomainException implements ExceptionInterface
{
}
