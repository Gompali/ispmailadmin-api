<?php

declare(strict_types=1);

namespace App\App\Command;

use App\Domain\DTO\UserDTO;

class PatchUserCommand
{
    /** @var array<mixed> */
    private $payload = [];

    public function __construct(UserDTO $DTO, string $id)
    {
        $this->payload = array_merge($DTO->toArray(), ['id' => $id]);
    }

    public function getId(): string
    {
        return $this->payload['id'];
    }

    public function getEmail(): ?string
    {
        return $this->payload['email'];
    }

    public function getPassword(): ?string
    {
        return $this->payload['password'];
    }

    public function getQuota(): ?int
    {
        return $this->payload['quota'];
    }
}
