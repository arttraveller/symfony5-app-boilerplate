<?php

namespace App\Exceptions;

use App\Exceptions\Interfaces\ExceptionInterface;

class UserNotActiveException extends \DomainException implements ExceptionInterface
{
}
