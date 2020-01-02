<?php

namespace App\Domain\Repository;

use App\Domain\VirtualDomains;

interface DomainRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findAll();

    public function save(VirtualDomains $domain);

    public function remove(VirtualDomains $domain);
}
