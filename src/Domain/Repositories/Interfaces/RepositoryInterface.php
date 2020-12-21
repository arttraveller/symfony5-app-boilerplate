<?php

namespace App\Domain\Repositories\Interfaces;

use App\Domain\Entities\Entity;
use App\Exceptions\EntityNotFoundException;

interface RepositoryInterface
{
    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object|null The object.
     *
     * @psalm-return T|null
     */
    public function find($id);

    /**
     * Finds all objects in the repository.
     *
     * @return array<int, object> The objects.
     *
     * @psalm-return T[]
     */
    public function findAll();

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param mixed[]       $criteria
     * @param string[]|null $orderBy
     * @param int|null      $limit
     * @param int|null      $offset
     *
     * @return object[] The objects.
     *
     * @throws UnexpectedValueException
     *
     * @psalm-return T[]
     */
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);

    /**
     * Finds a single object by a set of criteria.
     *
     * @param mixed[] $criteria The criteria.
     *
     * @return object|null The object.
     *
     * @psalm-return T|null
     */
    public function findOneBy(array $criteria);

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName();


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
