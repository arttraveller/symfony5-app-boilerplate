<?php

namespace App\Shared\Exceptions;

use DomainException;

class UserNotActiveException extends DomainException implements ExceptionInterface
{
}
