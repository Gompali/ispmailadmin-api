<?php

declare(strict_types=1);

namespace App\Infra\Repository;

use App\Domain\Repository\AliasRepositoryInterface;
use App\Domain\VirtualAliases;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AliasRepository extends ServiceEntityRepository implements AliasRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VirtualAliases::class);
    }

    public function remove(VirtualAliases $alias): void
    {
        $em = $this->getEntityManager();
        $em->remove($alias);
        $em->flush();
    }

    public function save(VirtualAliases $alias): void
    {
        $em = $this->getEntityManager();
        $em->persist($alias);
        $em->flush();
    }
}
