<?php

declare(strict_types=1);

namespace App\App\Command;

use App\Domain\DTO\UserDTO;

class PatchUserCommand
{
    private $payload = [];

    public function __construct(UserDTO $DTO, string $id)
    {
        $this->payload = array_merge($DTO->toArray(), ['id' => $id]);
    }

    public function getId()
    {
        return $this->payload['id'];
    }

    public function getEmail()
    {
        return $this->payload['email'];
    }

    public function getPassword()
    {
        return $this->payload['password'];
    }

    public function getQuota()
    {
        return $this->payload['quota'];
    }
}
