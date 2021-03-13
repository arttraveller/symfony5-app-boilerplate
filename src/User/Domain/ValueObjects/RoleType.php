<?php

namespace App\User\Domain\ValueObjects;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;


class RoleType extends StringType
{
    private const TYPE = 'user_role';


    public function getName()
    {
        return self::TYPE;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getName();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Role($value);
    }
}
