<?php

namespace App\Domain\Repository;

use App\Domain\AdminUser;

interface AdminUserRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function save(AdminUser $adminUser):void;
}
