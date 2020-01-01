<?php

namespace App\Domain\Repository;

interface DomainRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findAll();
}
