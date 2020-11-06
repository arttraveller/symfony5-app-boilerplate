<?php

namespace App\Exceptions;

use App\Exceptions\Interfaces\ExceptionInterface;

class ResetTokenAlreadyRequestedException extends \DomainException implements ExceptionInterface
{
}
