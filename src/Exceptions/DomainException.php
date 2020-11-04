<?php

namespace App\Exceptions;

use App\Exceptions\Interfaces\ExceptionInterface;

class DomainException extends \DomainException implements ExceptionInterface
{
}
