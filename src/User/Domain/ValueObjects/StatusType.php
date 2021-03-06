<?php

namespace App\User\Domain\ValueObjects;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;


class StatusType extends StringType
{
    private const TYPE = 'user_status';


    public function getName()
    {
        return self::TYPE;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getId();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Status($value);
    }
}
