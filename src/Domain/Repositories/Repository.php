<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Entity;
use App\Domain\Repositories\Interfaces\RepositoryInterface;
use App\Exceptions\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

abstract class Repository extends ServiceEntityRepository implements RepositoryInterface
{

    public function add(Entity $entity): void
    {
        $this->persist($entity);
        $this->flush();
    }


    public function persist(Entity $entity): void
    {
        $this->_em->persist($entity);
    }


    public function remove(Entity $entity): void
    {
        $this->_em->remove($entity);
    }


    public function flush(): void
    {
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(array $orderBy = ['id' => 'DESC']): array
    {
        return $this->findBy([], $orderBy);
    }


    /**
     * {@inheritdoc}
     */
    public function getOneBy(array $criteria): Entity
    {
        $entity = $this->findOneBy($criteria);
        if (!$entity) {
            throw new EntityNotFoundException('Entity was not found.');
        }

        return $entity;
    }

}
