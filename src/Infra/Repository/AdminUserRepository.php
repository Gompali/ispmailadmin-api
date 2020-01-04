<?php

declare(strict_types=1);

namespace App\Infra\Repository;

use App\Domain\AdminUser;
use App\Domain\Repository\AdminUserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AdminUserRepository extends ServiceEntityRepository implements AdminUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminUser::class);
    }

    public function save(AdminUser $adminUser): void
    {
        $em = $this->getEntityManager();
        $em->persist($adminUser);
        $em->flush($adminUser);
    }
}
