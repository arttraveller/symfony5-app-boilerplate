<?php

namespace App\Repositories;

use App\Domain\Entities\Post\Post;
use App\Domain\Repositories\Interfaces\PostsRepositoryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

class PostsRepository extends Repository implements PostsRepositoryInterface
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
