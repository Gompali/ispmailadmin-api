<?php

declare(strict_types=1);

namespace App\App\Command;

use App\Domain\DTO\UserDTO;

class UpdateUserCommand
{
    /** @var array<mixed> */
    private $payload = [];

    public function __construct(UserDTO $userDTO, string $email)
    {
        $this->payload = array_merge(
            $userDTO->toArray(), [
                'email' => $email,
            ]
        );
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->payload['email'];
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
}
