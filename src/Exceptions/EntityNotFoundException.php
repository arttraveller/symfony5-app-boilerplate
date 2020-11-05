<?php

namespace App\Exceptions;

use App\Exceptions\Interfaces\ExceptionInterface;

class EntityNotFoundException extends \Doctrine\ORM\EntityNotFoundException implements ExceptionInterface
{
}
