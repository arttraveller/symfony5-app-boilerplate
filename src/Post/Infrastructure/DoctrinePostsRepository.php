<?php

namespace App\Post\Infrastructure;

use App\Post\Application\PostsRepositoryInterface;
use App\Post\Domain\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePostsRepository extends ServiceEntityRepository implements PostsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function create(Post $post): void
    {
        $this->_em->persist($post);
        $this->_em->flush();
    }

    public function update(Post $post): void
    {
        $this->_em->flush();
    }

    public function delete(Post $post): void
    {
        $this->_em->remove($post);
    }
}
