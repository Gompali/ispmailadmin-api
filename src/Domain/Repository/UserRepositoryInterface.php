<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\VirtualUsers;

interface UserRepositoryInterface
{
    public function save(VirtualUsers $user): void;

    public function find($id, $lockMode = null, $lockVersion = null);

    public function findAll();

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function remove(VirtualUsers $user): void;
}
