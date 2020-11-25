<?php

namespace App\Core\Repositories;

use App\Core\Entities\Post\Post;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

class PostsRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAllWithUser(): Query
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'u')
            ->innerJoin('t.user', 'u', Join::WITH)
            ->orderBy('t.id', 'DESC')
            ->getQuery();
    }
}
