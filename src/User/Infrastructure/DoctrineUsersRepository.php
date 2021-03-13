<?php

namespace App\User\Infrastructure;

use App\User\Application\UsersRepositoryInterface;
use App\User\Domain\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineUsersRepository extends ServiceEntityRepository implements UsersRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function create(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function update(User $user): void
    {
        $this->_em->flush();
    }

    public function delete(User $user): void
    {
        $this->_em->remove($user);
    }
}
