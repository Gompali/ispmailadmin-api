<?php

declare(strict_types=1);

namespace App\Infra\Factory;

use App\Domain\DTO\UserDTO;
use App\Domain\VirtualUsers;

class UserFactory
{
    public static function createDTO(VirtualUsers $user)
    {
        $dto = new UserDTO();
        $dto->email = $user->getEmail();
        $dto->quota = $user->getQuota();
        return $dto;
    }
}
