<?php

declare(strict_types=1);

namespace App\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class AdminUser implements UserInterface
{
    /** @var string */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var array<string> */
    private $roles;

    public function __construct(
        string $id,
        string $username
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->roles = ['ROLE_ADMIN'];
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
