<?php

namespace App\Core\Repositories;

use App\Core\Entities\Post\Post;
use Doctrine\Persistence\ManagerRegistry;

class PostsRepository extends Repository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAll()
    {
        return $this->findBy([], ['id' => 'DESC']);
    }
}
