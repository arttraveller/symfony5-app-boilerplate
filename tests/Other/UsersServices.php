<?php

namespace App\Tests\Other;

use App\Tests\FunctionalTester;
use App\User\Application\QueryServices\UsersQueryServiceInterface;
use App\User\Application\UsersRepositoryInterface;

trait UsersServices
{
    protected function getUsersQueryService(FunctionalTester $I): UsersQueryServiceInterface
    {
        return $I->grabService(UsersQueryServiceInterface::class);
    }

    protected function getUsersRepository(FunctionalTester $I): UsersRepositoryInterface
    {
        return $I->grabService(UsersRepositoryInterface::class);
    }
}
