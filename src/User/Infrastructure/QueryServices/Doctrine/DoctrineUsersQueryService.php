<?php

namespace App\User\Infrastructure\QueryServices\Doctrine;

use App\Shared\Exceptions\UserNotFoundException;
use App\User\Application\QueryServices\UsersQueryServiceInterface;
use App\User\Domain\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineUsersQueryService extends ServiceEntityRepository implements UsersQueryServiceInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function existsByEmail(string $email): bool
    {
        return $this->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.email = :email')
                ->setParameter(':email', $email)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @inheritDoc
     */
    public function getOneByEmail(string $email): User
    {
        return $this->getOneBy(['email' => $email]);
    }

    /**
     * @inheritDoc
     */
    public function getOneById(int $id): User
    {
        return $this->getOneBy(['id' => $id]);
    }

    /**
     * @inheritDoc
     */
    public function getOneBy(array $criteria): User
    {
        $user = $this->findOneBy($criteria);
        if (!$user) {
            throw new UserNotFoundException('Entity was not found.');
        }

        return $user;
    }
}
