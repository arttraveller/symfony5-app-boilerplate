<?php

namespace App\Domain\Repositories\Interfaces;

use Doctrine\ORM\Query;

interface PostsRepositoryInterface extends RepositoryInterface
{
    public function findAllWithUser(): Query;
}
