<?php

namespace App\Post\Infrastructure\QueryServices\Doctrine;

use App\Post\Application\QueryServices\PostsQueryServiceInterface;
use App\Post\Domain\Post;
use App\Shared\Exceptions\ResourceNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

class DoctrinePostsQueryService extends ServiceEntityRepository implements PostsQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @inheritDoc
     */
    public function getOneById(int $id): Post
    {
        return $this->getOneBy(['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function getOneBy(array $criteria): Post
    {
        $post = $this->findOneBy($criteria);
        if (!$post) {
            throw new ResourceNotFoundException('Post not found.');
        }

        return $post;
    }

    /**
     * @inheritDoc
     */
    public function findAllWithUser(): Query
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'u')
            ->innerJoin('t.user', 'u', Join::WITH)
            ->orderBy('t.id', 'DESC')
            ->getQuery();
    }
}
