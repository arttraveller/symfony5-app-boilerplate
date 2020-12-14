<?php

namespace App\Domain\Repositories\Interfaces;

use App\Domain\Entities\Entity;
use App\Exceptions\EntityNotFoundException;
use Doctrine\Persistence\ObjectRepository;

interface RepositoryInterface extends ObjectRepository
{

    public function add(Entity $entity): void;

    public function persist(Entity $entity): void;

    public function remove(Entity $entity): void;

    public function flush(): void;

    /**
     * Gets a single entity by criteria.
     *
     * @param array $criteria
     * @return Entity entity instance
     * @throws EntityNotFoundException
     */
    public function getOneBy(array $criteria): Entity;
}
