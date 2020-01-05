<?php

declare(strict_types=1);

namespace App\App\Command;

use App\Domain\DTO\UpdateUserDTO;

class UpdateUserCommand
{
    /** @var array<mixed> */
    private $payload = [];

    public function __construct(UpdateUserDTO $userDTO, string $username)
    {
        $this->payload = array_merge(
            $userDTO->toArray(), [
                'username' => $username,
            ]
        );
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->payload['password'];
    }

    /**
     * @return int
     */
    public function getQuota(): int
    {
        return (int) $this->payload['quota'];
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->payload['domain'];
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->payload['username'];
    }
}
