<?php

declare(strict_types=1);

namespace App\Infra\Repository;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\VirtualUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VirtualUsers::class);
    }

    public function save(VirtualUsers $user)
    {
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    public function remove(string $email)
    {
        $user = $this->findOneBy([
            'email' => $email,
        ]);

        $em = $this->getEntityManager();
        $em->remove($user);
        $em->flush();
    }
}
