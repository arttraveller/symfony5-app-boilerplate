<?php

namespace App\Post\Application\QueryServices;

use App\Post\Domain\Post;
use App\Shared\Exceptions\ResourceNotFoundException;
use Doctrine\ORM\Query;

interface PostsQueryServiceInterface
{
    /**
     * @param int $id
     * @return Post
     * @throws ResourceNotFoundException
     */
    public function getOneById(int $id): Post;

    /**
     * @param array $criteria
     * @return Post
     * @throws ResourceNotFoundException
     */
    public function getOneBy(array $criteria): Post;

    /**
     * @todo temp for test
     * @todo fix it
     */
    public function findAllWithUser(): Query;
}
