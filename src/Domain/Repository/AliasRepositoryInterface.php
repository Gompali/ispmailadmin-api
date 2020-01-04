<?php

namespace App\Domain\Repository;

use App\Domain\VirtualAliases;

interface AliasRepositoryInterface
{
    public function findOneBy(array $criteria, array $orderBy = null);

    public function remove(VirtualAliases $alias): void;

    public function save(VirtualAliases $alias): void;
}
