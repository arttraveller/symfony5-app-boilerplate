<?php

namespace App\Domain\Fetchers\Interfaces;

use App\Domain\Entities\User\User;

interface CurrentUserFetcherInterface
{
    public function getUser(): User;
}
