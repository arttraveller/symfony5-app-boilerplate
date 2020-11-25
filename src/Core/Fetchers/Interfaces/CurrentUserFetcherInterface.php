<?php

namespace App\Core\Fetchers\Interfaces;

use App\Core\Entities\User\User;

interface CurrentUserFetcherInterface
{
    public function getUser(): User;
}
