<?php

declare(strict_types=1);

namespace App\Infra\Repository;

use App\Domain\Repository\DomainRepositoryInterface;
use App\Domain\VirtualDomains;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DomainRepository extends ServiceEntityRepository implements DomainRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VirtualDomains::class);
    }

    public function remove(VirtualDomains $domain):void
    {
        $em = $this->getEntityManager();
        $em->remove($domain);
        $em->flush();
    }

    public function save(VirtualDomains $domain):void
    {
        $em = $this->getEntityManager();
        $em->persist($domain);
        $em->flush();
    }
}
